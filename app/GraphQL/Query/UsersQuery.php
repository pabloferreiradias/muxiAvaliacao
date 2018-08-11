<?php

namespace App\GraphQL\Query;

use App\Models\User;
use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;
use GraphQL\Type\Definition\ResolveInfo;
use App\GraphQL\Type\UsersType;

class UsersQuery extends Query
{
    protected $attributes = [
        'name' => 'Users',
        'description' => 'List users'
    ];

    public function type()
    {
        return GraphQL::paginate('user');
    }

    public function args()
    {
        return [
            'id' => [
                'name' => 'id',
                'type' => Type::int(),
                'description' => 'User ID',
            ],
            'login' => [
                'name' => 'login',
                'type' => Type::string(),
                'description' => 'User Login',
            ]
        ];
    }

    public function resolve($root, $args, SelectFields $fields, ResolveInfo $info)
    {
        $select = $fields->getSelect();
        $with = $fields->getRelations();

        $where = function ($query) use ($args) {
            if (isset($args['id'])) {
                $query->where('id',$args['id']);
            }
            if (isset($args['login'])) {
                $query->where('login',$args['login']);
            }
        };
        $user = User::with(array_keys($with))
            ->where($where)
            ->select($select)
            ->paginate();
        return $user;
    }
}
