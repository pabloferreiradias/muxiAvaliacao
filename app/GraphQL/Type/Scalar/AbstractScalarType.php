<?php
namespace App\GraphQL\Type\Scalar;

use GraphQL\Type\Definition\ScalarType;

abstract class AbstractScalarType extends ScalarType
{
    public function toType()
    {
        return new static($this->toArray());
    }

    /**
     * Convert the Fluent instance to an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'serialize' => function ($value) {
                return $this->serialize($value);
            },
            'parseValue' => function ($value) {
                return $this->parseValue($value);
            },
            'parseLiteral' => function ($valueNode) {
                return $this->parseLiteral($valueNode);
            },
        ];
    }
}
