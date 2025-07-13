<?php

namespace App\Models\Backups;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;
use App\Traits\TableFormData\Backup as TableFormDataTrait;

class Backup
{
    use TableFormDataTrait;

    private string $disk;
    private string $backup_path;
    private array $filters = [];

    /**
     * Dynamically retrieve attributes on the model.
     *
     * @param string $key
     * @return mixed
     */
    public function __get(string $key)
    {
        return $this->$key ?? null;
    }

    /**
     * Dynamically set attributes on the model.
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function __set(string $key, $value): void
    {
        $this->$key = $value;
    }

    /**
     * Check if an attribute is set on the model.
     *
     * @param string $key
     * @return bool
     */
    public function __isset(string $key): bool
    {
        return isset($this->$key);
    }

    /**
     * Unset an attribute on the model.
     *
     * @param string $key
     * @return void
     */
    public function __unset(string $key): void
    {
        unset($this->$key);
    }

    public function __construct()
    {
        $this->disk = config('filesystems.backup.driver', 'local');
        $this->backup_path = 'backups/';
    }

    /**
     * Add a filter to the query.
     *
     * @param string $field The field to filter by.
     * @param string $operator The operator to use (e.g., '=', 'like').
     * @param mixed $value The value to filter by.
     * @return $this
     */
    public function where(string $field, string $operator, $value): self
    {
        $this->filters[] = compact('field', 'operator', 'value');
        return $this;
    }

    /**
     * Get all backups with applied filters.
     *
     * @return Collection
     */
    public function get(): Collection
    {
        $files = Storage::disk($this->disk)->files($this->backup_path);

        // Aplica filtros simples por nombre
        foreach ($this->filters as $filter) {
            [$field, $operator, $value] = array_values($filter);
            if ($field === 'name') {
                if ($operator === '=') {
                    $files = array_filter($files, fn($file) => basename($file) === $value);
                } elseif ($operator === 'like') {
                    $files = array_filter($files, fn($file) => stripos(basename($file), $value) !== false);
                }
            }
        }

        return collect(array_values($files))->map(function ($file) {
            $model = new self();
            $model->name = basename($file);
            $model->path = $file;
            return $model;
        });
    }

    /**
     * Paginate the backups.
     *
     * @param int $perPage The number of items per page.
     * @param int $page The current page.
     * @return array
     */
    public function paginate(int $perPage = 10, int $page = 1): \Illuminate\Pagination\LengthAwarePaginator
    {
        $allBackups = $this->get();
        $total = $allBackups->count();
        $offset = ($page - 1) * $perPage;

        $paginatedItems = $allBackups->slice($offset, $perPage)->values();

        return new \Illuminate\Pagination\LengthAwarePaginator(
            $paginatedItems,
            $total,
            $perPage,
            $page,
            [
                'path' => request()->url(),
                'query' => request()->query(),
            ]
        );
    }

    /**
     * Static query method to mimic Eloquent's query builder.
     *
     * @return self
     */
    public static function query(): self
    {
        return new self();
    }

    public function delete($name)
    {
        $file = $this->backup_path . $name;
        if (Storage::disk($this->disk)->exists($file)) {
            return Storage::disk($this->disk)->delete($file);
        }
        throw new \Exception("File not found: $name");
    }

    public function download($name)
    {
        $file = $this->backup_path . $name;
        if (Storage::disk($this->disk)->exists($file)) {
            return Storage::disk($this->disk)->path($file);
        }
        throw new \Exception("File not found: $name");
    }
}