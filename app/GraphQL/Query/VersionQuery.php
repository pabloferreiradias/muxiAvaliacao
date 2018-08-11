<?php

namespace App\GraphQL\Query;

use GraphQL;
use Rebing\GraphQL\Support\Query;

class VersionQuery extends Query
{
    protected $attributes = [
        'name' => 'VersionQuery',
        'description' => 'Project version info',
    ];

    public function type()
    {
        return GraphQL::type('version');
    }

    /**
     * @SuppressWarnings("unused")
     */
    public function resolve($root, $args)
    {
        return ['version' => '1.0'];
    }
}
