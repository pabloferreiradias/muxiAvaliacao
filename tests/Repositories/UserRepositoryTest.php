<?php
namespace Tests\Repositories;

use App\Models\User;
use App\Repositories\UserRepository;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    public function testFindByLoginReturnsUser()
    {
        $repository = new UserRepository;
        $searched = factory(User::class)->create();
        $user = $repository->findByLogin($searched->login);
        $this->assertInstanceOf(User::class, $user);
    }

    public function testFindByLoginReturnsNullIfNotFound()
    {
        $repository = new UserRepository;
        $user = $repository->findByLogin('unexistant_login');
        $this->assertNull($user);
    }
}
