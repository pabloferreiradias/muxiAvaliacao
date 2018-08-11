<?php

namespace Tests\GraphQL\Type;

use \App\GraphQL\Type\TokenType;

class TokenTypeTest extends TestCase
{
    public function getType()
    {
        return new TokenType();
    }
}