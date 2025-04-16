<?php

namespace App\Models\Backups;

use Everth\Rclonemanager\Rclone;
use Illuminate\Support\Collection;
use App\Traits\TableFormData\Backup as TableFormDataTrait;

class Backup
{
    use TableFormDataTrait;

    private Rclone $rclone;
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
        $this->rclone = new Rclone(env('RCLONE_CONFIG_FILE_PATH'));
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
        $backups = $this->rclone->list(env('RCLONE_CONFIG_NAME') . ": " . env('RCLONE_BACKUP_PATH'));
        $backups = mb_split("\n", $backups['output']);

        // Apply filters
        foreach ($this->filters as $filter) {
            $backups = array_filter($backups, function ($backup) use ($filter) {
                [$field, $operator, $value] = array_values($filter);

                // Example: Apply basic filtering logic
                if ($operator === '=') {
                    return strpos($backup, $value) !== false;
                }

                if ($operator === 'like') {
                    return stripos($backup, $value) !== false;
                }

                return true;
            });
        }

        return collect(array_values($backups))->map(function ($backup) {
            $model = new self();
            $model->name = $backup;
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
}