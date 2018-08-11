<?php
namespace Tests\Http\Middleware;

use App\Http\Middleware\AcceptJson;
use Illuminate\Http\Request;
use Mockery;
use Tests\TestCase;

class AcceptJsonTest extends TestCase
{
    public function testHandleIncludeAcceptJsonHeaderInTheRequest()
    {
        $request = Mockery::mock(Request::class . '[]');
        $middleware = new AcceptJson;
        $middleware->handle(
            $request,
            function ($request) {
            },
            null
        );

        $this->assertEquals(
            'application/json',
            $request->headers->get('accept')
        );
    }
}
