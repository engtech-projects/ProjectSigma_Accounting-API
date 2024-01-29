<?php
namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;

class HrmsUser extends Model implements AuthenticatableContract
{

    use HasFactory,Authorizable ;
    //protected $table = 'auth_users';

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
    public function getAuthIdentifier()
    {
        return $this->getAttributeFromArray('user_id');
    }
    public function getAuthPassword()
    {
        return null;
    }
    public function getRememberToken()
    {
        return null;
    }
    public function setRememberToken($value)
    {
    }
    public function getRememberTokenName()
    {

    }

    public function getAccessibilities()
    {
        $accessibilities = $this->getAttributeFromArray('accessibilities');
        $userAcess = [];
        $accessGroup = 'accounting:';
        foreach ($accessibilities as $key => $value) {
            if(str_starts_with($value,$accessGroup)) {
                array_push($userAcess,$value);
            }
        }
        return $accessibilities;

    }

}
