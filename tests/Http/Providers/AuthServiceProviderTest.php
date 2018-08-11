<?php

namespace Tests\Http\Providers;

use App\Providers\AuthServiceProvider;
use Mockery;
use Tests\TestCase;

class AuthServiceProviderTest extends TestCase
{
    public function testBootRegisterPolicies()
    {
        // This is a workaround because Mockery does not support
        // partial mocks with disabled constructors
        // @see https://github.com/mockery/mockery/issues/453
        $provider = Mockery::mock(AuthServiceProvider::class, [null => null])->makePartial();
        $provider->shouldReceive('registerPolicies')->once();
        $provider->boot();
    }
}
