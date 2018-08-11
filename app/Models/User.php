<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class User extends BaseModel implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    protected $fillable = [
        'name',
        'login',
    ];

    protected $hidden = [
        'password',
    ];

    public $rules = [
        'name' => 'required',
        'login' => 'required',
    ];

    public function userable()
    {
        return $this->morphTo();
    }

    public function isAdministrator()
    {
        return ($this->userable_id === null && $this->userable_type === null);
    }
}
