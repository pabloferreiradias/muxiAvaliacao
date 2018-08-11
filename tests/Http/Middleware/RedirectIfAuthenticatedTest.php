<?php
namespace Tests\Http\Middleware;

use App\Http\Middleware\RedirectIfAuthenticated;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Mockery;
use Tests\TestCase;

class RedirectIfAuthenticatedTest extends TestCase
{
    public function testHandleRedirectsIfUserAuthenticated()
    {
        $guard = Mockery::mock(Guard::class . '[check]', function ($mock) {
            $mock->shouldReceive('check')->andReturn(true);
        });
        Auth::shouldReceive('guard')->andReturn($guard);
        Redirect::shouldReceive('to');
        $request = Mockery::mock(Request::class);
        $middleware = new RedirectIfAuthenticated;
        $middleware->handle(
            $request,
            function ($request) {
            },
            null
        );
    }

    public function testHandleDelegatesToNextIfUserIsGuest()
    {
        $guard = Mockery::mock(Guard::class . '[check]', function ($mock) {
            $mock->shouldReceive('check')->andReturn(false);
        });
        Auth::shouldReceive('guard')->andReturn($guard);
        $request = Mockery::mock(Request::class);
        $middleware = new RedirectIfAuthenticated;
        $return = $middleware->handle(
            $request,
            function ($request) {
                return 'delegated';
            },
            null
        );
        $this->assertEquals('delegated', $return);
    }
}
