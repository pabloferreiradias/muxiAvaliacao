<?php
namespace Tests\Exceptions;

use App\Exceptions\Handler;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Redirect;
use Mockery;
use Tests\TestCase;

class HandlerTest extends TestCase
{
    public function testUnauthenticatedExceptionWithJson()
    {
        Response::shouldReceive('json')->with(['error' => 'Unauthenticated.'], 401);
        $handler = resolve(Handler::class);
        $exception = new AuthenticationException;
        $request = Mockery::mock(Request::class . '[expectsJson]', function ($mock) {
            $mock->shouldReceive('expectsJson')->andReturn(true);
        });
        $handler->render($request, $exception);
    }

    public function testUnauthenticatedExceptionOnWeb()
    {
        Redirect::shouldReceive('guest');
        $handler = resolve(Handler::class);
        $exception = new AuthenticationException;
        $request = Mockery::mock(Request::class . '[expectsJson]', function ($mock) {
            $mock->shouldReceive('expectsJson')->andReturn(false);
        });
        $handler->render($request, $exception);
    }
}
