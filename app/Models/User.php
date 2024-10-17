<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, Authorizable, HasApiTokens;

	public function getAuthIdentifierName()
    {
        return [
            'user_id' => 'id',
            'email' => 'email',
            'name' => 'name',
            'type' => 'user',
            'accessibilities' => 'accessibilities'
        ];
    }

	public function getAccessibilities()
    {
        $accessibilities = $this->getAttributeFromArray('accessibilities');
        $userAcess = [];
        $accessGroup = 'accounting:';
        foreach ($accessibilities as $key => $value) {
            if (str_starts_with($value, $accessGroup)) {
                array_push($userAcess, $value);
            }
        }
        return $accessibilities;

    }

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
}
