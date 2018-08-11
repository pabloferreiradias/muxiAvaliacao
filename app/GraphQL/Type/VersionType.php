<?php

namespace App\GraphQL\Type;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class VersionType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Version',
        'description' => 'A version type'
    ];

    public function fields()
    {
        return [
            'version' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The project current version',
            ],
        ];
    }
}
