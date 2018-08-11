<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateUser extends Command
{
    use CreateUserTrait;

    /**
     * @var string
     */
    protected $signature = 'users:create';

    /**
     * @var string
     */
    protected $description = 'Creates an administrator user';

    /**
     * @return mixed
     */
    public function handle()
    {
        $this->createUser();
    }
}
