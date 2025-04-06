<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Users\UserModule;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Users\Module;
use App\Traits\TableFormData\User as TableFormDataUser;

class User extends Authenticatable
{
    use HasFactory, Notifiable, TableFormDataUser;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // 0: Administrador, 1: Usuario
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function userModule()
    {
        return $this->hasMany(UserModule::class);
    }

    public function modules()
    {
        if($this->isAdmin()) {
            return Module::all();
        }

        $modules = new Collection();

        foreach ($this->userModule as $userModule) {
            $modules->push($userModule->module);
        }

        return $modules;
    }

    public function isAdmin(): bool
    {
        return $this->role === '0';
    }

    public function isUser(): bool
    {
        return $this->role === '1';
    }
}
