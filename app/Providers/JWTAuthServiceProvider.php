<?php

namespace App\Providers;

use App\JWTAuth\PayloadFactory;
use Tymon\JWTAuth\Providers\JWTAuthServiceProvider as OriginalJWTAuthServiceProvider;

class JWTAuthServiceProvider extends OriginalJWTAuthServiceProvider
{
    /**
     * Register the bindings for the Payload Factory.
     */
    protected function registerPayloadFactory()
    {
        $this->app->singleton('tymon.jwt.payload.factory', function ($app) {
            $factory = new PayloadFactory(
                $app['tymon.jwt.claim.factory'],
                $app['request'],
                $app['tymon.jwt.validators.payload']
            );

            return $factory->setTTL($this->config('ttl'));
        });
    }
}
