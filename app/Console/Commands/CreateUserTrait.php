<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Shares user creation code among multiple commands that create different users
trait CreateUserTrait
{
    /**
     * @return mixed
     */
    private function createUser() : User
    {
        $login = $this->ask(trans('user.login'));
        $name = $this->ask(trans('user.name'));
        $password = $this->secret(trans('user.password'));

        $user = new User;
        $user->name = trim($name);
        $user->login = trim($login);
        $user->password = Hash::make($password);
        $user->save();

        $this->info(trans('site.successfullyCreated'));

        return $user;
    }
}
