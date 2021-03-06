<?php

namespace App\Models;

use App\QueryBuilders\UserQueryBuilder;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    public const MEMBER = 'member';

    public const ADMIN = 'admin';

    /** @var array */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /** @var array */
    protected $casts = [
        'id' => 'integer',
        'email_verified_at' => 'datetime',
    ];

    /**
     * @param  \Illuminate\Database\Query\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function newEloquentBuilder($query)
    {
        return new UserQueryBuilder($query);
    }

    /** @return string */
    public function getProfileImageFileAttribute()
    {
        if ($this->profile_image === null) {
            return asset('images/default-user.png');
        }

        return Storage::disk('profile_image')->url("{$this->profile_image}");
    }

    /**
     * @param  string  $imageName
     * @return null
     */
    public function saveImage($imageName)
    {
        $this->update(['profile_image' => $imageName]);
    }

    /** @return bool */
    public function isAdmin()
    {
        return $this->role === self::ADMIN;
    }
}
