<?php

namespace Tests\Console\Commands;

use App\Console\Commands\CreateUser;
use App\Models\User;
use Mockery;
use Tests\TestCase;

class CreateUserTest extends TestCase
{
    public function testItCreatesAnUser()
    {
        $command = Mockery::mock(CreateUser::class . '[ask,secret,info]', function ($mock) {
            $mock->shouldReceive('ask')->andReturn('john');
            $mock->shouldReceive('secret')->andReturn('password');
            $mock->shouldReceive('info');
        });

        $command->handle();
        $user = User::first();

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('john', $user->login);
    }
}
