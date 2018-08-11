<?php

namespace App\GraphQL\Type;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class UserType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Users',
        'description' => 'List users',
        'model' => \App\Models\User::class,
    ];

    public function fields()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'User ID',
            ],
            'login' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Login',
            ],
            'name' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'User Name',
            ],
            'created_at' => [
                'type' => Type::string(),
                'description' => 'User Created At',
            ],
            'updated_at' => [
                'type' => Type::string(),
                'description' => 'User Last Updated',
            ]
        ];
    }
}
