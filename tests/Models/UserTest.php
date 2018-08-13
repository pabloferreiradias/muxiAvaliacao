<?php

namespace Tests\Models;

use App\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function testUserableReturnsNullForMissingPolymorphicAssociation()
    {
        $user = factory(User::class)->create();
        $this->assertNull($user->userable);
    }

    public function testIsAdministratorReturnsTrueWithoutPolymorphicAssociation()
    {
        $user = factory(User::class)->create([
            'userable_type' => null,
            'userable_id' => null,
        ]);
        $this->assertTrue($user->isAdministrator());
    }


}
