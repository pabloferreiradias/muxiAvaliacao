<?php

return [
    'prefix' => !empty(env('APP_PREFIX', '')) ? (env('APP_PREFIX', '') . '/graphql') : 'graphql',
    'routes' => '{graphql_schema?}',
    'controllers' => \Rebing\GraphQL\GraphQLController::class . '@query',
    'middleware' => [],
    'default_schema' => 'default',
    'schemas' => [
        'default' => [
            'query' => [
                'currentVersion' => \App\GraphQL\Query\VersionQuery::class,
                'users' => \App\GraphQL\Query\UsersQuery::class,
            ],
            'middleware' => ['jwt.auth:api']
        ],
        'public' => [
            'query' => [
                'currentVersion' => \App\GraphQL\Query\VersionQuery::class,
            ],
            'mutation' => [
                'createToken' => App\GraphQL\Mutation\CreateTokenMutation::class
            ],
        ]
    ],
    'types' => [
        // Scalar types
        'date' => App\GraphQL\Type\Scalar\DateType::class,
        'datetime' => App\GraphQL\Type\Scalar\DatetimeType::class,
        
        // Object types (used in "query")
        'version' => \App\GraphQL\Type\VersionType::class,
        'token' => App\GraphQL\Type\TokenType::class,
        'user' => App\GraphQL\Type\UserType::class,

        // Input types (used in "mutation")
    ],
    'error_formatter' => ['\Rebing\GraphQL\GraphQL', 'formatError'],
    'params_key' => 'variables',
];
