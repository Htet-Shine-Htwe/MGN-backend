<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Scope\AdminScope;
use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;


class Admin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable,HasRoles,AdminScope;

    protected string $guard_name = 'admin';


    // append role_name
    protected $appends = ['role_name','role_id'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'last_accessed_at',
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_accessed_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getRoleNameAttribute(): string
    {
        return ucFirst(optional($this->roles->first())->name) ;
    }

    public function getRoleIdAttribute(): int
    {
        return optional($this->roles->first())->id;
    }

    public function getAllPermissionsAttribute(): array
    {
         /** @var ?Role $firstRole */
        $firstRole = $this->roles->first();

        if (!$firstRole) {
            return [];
        }

        return array_values(array_unique($firstRole->permissions->pluck('name')->toArray()));
    }

    public function getLastAccessedAtAttribute(DateTime|null $value): string
    {
        return !is_null($value) ? $value->format('Y-m-d H:i:s') : '';
    }


    /**
     * chapters
     *
     * @return Collection<int, SubMogou>
     */
    public function chapters() : Collection
    {
        $subMoGou = new SubMogou();
        $tables = $subMoGou->getCreatedPartitions();
        $collection = [];
        foreach ($tables as $table) {
            $collection[] = $subMoGou->setTable($table)->where('creator_id', $this->id)->get();
        }

        return $subMoGou->newCollection($collection)->collapse();
    }

}
