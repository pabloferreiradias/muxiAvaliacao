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

    public function testSaveShouldValidate()
    {
        $user = factory(User::class)->create([
            'userable_type' => null,
            'userable_id' => null,
        ]);
        $user->name = '';
        $this->assertFalse($user->save());
        $user->name = 'opaopa';
        $this->assertTrue($user->save());
    }

    public function testShouldAllowSaveWithoutValidate()
    {
        $user = factory(User::class)->create([
            'userable_type' => null,
            'userable_id' => null,
        ]);
        $user->name = '';
        $this->assertTrue($user->saveWithoutValidate());
    }


}
