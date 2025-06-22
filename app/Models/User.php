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
use App\Models\Users\UserModelFilter;
use App\Traits\ModelFilters\HasUserModelFilters;

/**
 * Class User
 * Represents the User model, which extends the Authenticatable class.
 * This model handles user-related data and relationships.
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable, TableFormDataUser, HasUserModelFilters;

    /**
     * @var array<int, string> The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // 0: Administrator, 1: User
        'can_login', // Indicates if the user can log in
    ];

    /**
     * @var array<int, string> The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string> The attributes and their cast types.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

        public static $available_model_filters = [
        'simple' => [
            'label' => 'Filtros simples',
            'type' => self::TYPE_SIMPLE,
            'fields' => [
                'role' => [
                    'label' => 'Rol del usuario',
                    'type' => self::TYPE_STATIC_SELECT,
                    'values' => [
                        '0' => 'Administrador',
                        '1' => 'Usuario',
                    ],
                ],
                'can_login' => [
                    'label' => 'Puede iniciar sesión',
                    'type' => self::TYPE_STATIC_SELECT,
                    'values' => [
                        '1' => 'Sí',
                        '0' => 'No',
                    ],
                ],
            ],
            'operators' => [
                self::OP_EQUAL => [
                    'label' => 'Igual',
                    'value' => self::OP_EQUAL,
                ],
                self::OP_NOT_EQUAL => [
                    'label' => 'Diferente',
                    'value' => self::OP_NOT_EQUAL,
                ],
            ],
        ],
        //ejemplo de filtros que se van a poder usar en el futuro
        /* 'relations' => [
            'label' => 'Filtros por relaciones',
            'type' => self::TYPE_RELATIONS,
            'direction' => [
                'fields' => [
                    'name' => [
                        'type' => self::TYPE_DYNAMIC_SELECT,
                        'model' => 'Direction',
                    ],
                ],
                'operators' => [
                    self::OP_EQUAL => 'Igual',
                    self::OP_NOT_EQUAL => 'Diferente',
                ],
            ],
        ], */
        /* 'functions' => [
            'label' => 'Filtros por funciones',
            'type' => self::TYPE_FUNCTIONS,
            'salaryMayorThan' => [
                'label' => 'Salario mayor que',
                'method' => 'where',
                'fields' => [
                    'salary' => [
                        'type' => self::TYPE_OPEN,
                    ],
                ],
                'operators' => [
                    self::OP_GREATER => 'Mayor que',
                ],
            ],
        ], */
    ];

    /**
     * Defines a one-to-many relationship with the UserModule model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userModule()
    {
        return $this->hasMany(UserModule::class);
    }

    /**
     * Defines a one-to-many relationship with the UserModelFilter model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userModelFilters()
    {
        return $this->hasMany(UserModelFilter::class);
    }

    /**
     * Retrieves the modules associated with the user.
     * If the user is an administrator, all modules are returned.
     *
     * @return \Illuminate\Database\Eloquent\Collection The collection of modules.
     */
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

    
    /**
     * Checks if the user has access to a specific module and method.
     *
     * @return bool True if the user is an administrator or has access to the module and method, false otherwise.
     */
    public function hasAccessToModule(string $module, string $method): bool
    {
        if($this->isAdmin()) return true;

        $action_names = [
            'GET' => 'read',
            'HEAD' => 'read',
            'OPTIONS' => 'read',
            'POST' => 'create',
            'PUT' => 'update',
            'PATCH' => 'update',
            'DELETE' => 'delete',
        ];

        return $this->userModule()->whereHas('module', function($query) use ($module) {
            $query->where('internal_name', $module);          
        })
        ->whereHas('actions', function($query) use ($method, $action_names) {
            $query->where('action', $action_names[$method]);
        })
        ->exists();
    }

    /**
     * Checks if the user has an administrator role.
     *
     * @return bool True if the user is an administrator, false otherwise.
     */
    public function isAdmin(): bool
    {
        return $this->role === '0';
    }

    /**
     * Checks if the user has a regular user role.
     *
     * @return bool True if the user is a regular user, false otherwise.
     */
    public function isUser(): bool
    {
        return $this->role === '1';
    }
}
