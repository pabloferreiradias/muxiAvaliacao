<?php

namespace Tests\Http\Controllers;

use App\Http\Controllers\AuthenticationsController;
use Illuminate\Http\Request;
use JWTAuth;
use Mockery;
use Tests\TestCase;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthenticationsControllerTest extends TestCase
{
    public function testCreateFailsForUserWithWrongPassword()
    {
        JWTAuth::shouldReceive('attempt')->andReturn(false);
        $request = $this->getRequestWith('john', 'wront_secret');
        $controller = new AuthenticationsController;
        $response = $controller->create($request);
        $this->assertEquals(401, $response->status());
    }

    public function testCreateSucceedsForUserWithValidCredentials()
    {
        JWTAuth::shouldReceive('attempt')->andReturn(true);
        $request = $this->getRequestWith('john', 'secret');
        $controller = new AuthenticationsController;
        $response = $controller->create($request);
        $this->assertEquals(200, $response->status());
    }

    public function testCreateDisplayErrorIfTokenCretionFails()
    {
        $exception = new JWTException;
        JWTAuth::shouldReceive('attempt')->andThrow($exception);
        $request = $this->getRequestWith('john', 'secret');
        $controller = new AuthenticationsController;
        $response = $controller->create($request);
        $this->assertEquals(500, $response->status());
    }

    protected function getRequestWith($login, $password)
    {
        return Mockery::mock(
            Request::class . '[only]',
            function ($mock) use ($login, $password) {
                $mock->shouldReceive('only')->andReturn([
                    'login' => $login,
                    'password' => $password,
                ]);
            }
        );
    }
}
