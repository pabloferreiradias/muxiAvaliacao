<?php

namespace Tests\GraphQL;

trait GraphQL
{

    /**
     * Request GraphQL Endpoint.
     *
     * @param  string $query
     * @param  string $endpoint
     * @param  array  $variables
     * @return void
     */
    protected function graphql($query, $endpoint = 'default', array $variables = [])
    {
        return $this->json('POST', '/graphql/' . $endpoint, compact('query', 'variables'));
    }

}
