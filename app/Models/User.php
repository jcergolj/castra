<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'email_verified_at' => 'datetime',
    ];

    /**
     *  Get user's image file.
     *
     * @return string
     */
    public function getProfileImageFileAttribute()
    {
        if ($this->profile_image === null) {
            return asset('images/default-user.png');
        }

        return Storage::disk('profile_image')->url("{$this->profile_image}");
    }

    /**
     * Save user's image name.
     *
     * @param  string  $imageName
     * @return null
     */
    public function saveImage($imageName)
    {
        $this->update(['profile_image' => $imageName]);
    }
}
