<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\Can;
use Illuminate\Contracts\Auth\Authenticatable as AuthAuthenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements AuthAuthenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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
        'password'          => 'hashed',
    ];

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }

    public function givePermissionTo(Can|string $key)
    {
        $pKey = $key instanceof Can ? $key->value : $key;

        $this->permissions()->firstOrCreate(['key' => $pKey]);

        Cache::forget($this->getPermissionCacheKey());

        Cache::rememberForever($this->getPermissionCacheKey(), fn () => $this->permissions);
    }

    public function hasPermissionTo(Can|string $key)
    {
        $pKey = $key instanceof Can ? $key->value : $key;

        /** @var \Illuminate\Database\Eloquent\Collection<\App\Models\Permission> */
        $permissions = Cache::get($this->getPermissionCacheKey(), $this->permissions);

        return $permissions->where('key', '=', $pKey)->isNotEmpty();
    }

    private function getPermissionCacheKey()
    {
        return "user::{$this->id}::permissions";
    }
}
