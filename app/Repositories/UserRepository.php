<?php
namespace App\Repositories;

use App\Models\User;

/**
 * Created as a layer between the controller and an Eloquent model,
 * so that it can be mocked out in tests and also provide query scopes.
 */
class UserRepository
{
    public function findByLogin($login)
    {
        return User::where('login', $login)->first();
    }
}
