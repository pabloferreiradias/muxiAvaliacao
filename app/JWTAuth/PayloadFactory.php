<?php
namespace App\JWTAuth;

use Tymon\JWTAuth\PayloadFactory as OldPayloadFactory;

class PayloadFactory extends OldPayloadFactory
{
    /**
     * @var array
     */
    protected $defaultClaims = ['sub', 'iat', 'exp', 'jti'];
}
