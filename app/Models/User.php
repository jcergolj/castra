<?php

namespace App\Models;

use App\Enums\UserRoles;
use Illuminate\Database\Query\Builder;
use App\QueryBuilders\UserQueryBuilder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use App\Models\Concerns\LogsDeleteActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, SoftDeletes, LogsDeleteActivity;

    /** @var array<int, string> */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /** @var array<string, string> */
    protected $casts = [
        'id' => 'integer',
        'email_verified_at' => 'datetime',
        'deleted_at' => 'datetime',
        'role' => UserRoles::class,
    ];

    public function restoreRouteName(): string
    {
        return 'admin.users.restore';
    }

     /**
     * @param Builder $query
     */
    public function newEloquentBuilder($query): UserQueryBuilder
    {
        return new UserQueryBuilder($query);
    }

    public function getProfileImageFileAttribute(): string
    {
        if ($this->profile_image === null) {
            return asset('images/default-user.png');
        }

        return Storage::disk('profile_image')->url("{$this->profile_image}");
    }

    public function saveImage(string $imageName): void
    {
        $this->update(['profile_image' => $imageName]);
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRoles::Admin;
    }

    public function isItMe(): bool
    {
        return $this->is(user());
    }
}
