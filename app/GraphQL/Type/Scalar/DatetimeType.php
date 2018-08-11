<?php
namespace App\GraphQL\Type\Scalar;

use GraphQL\Error\Error;
use GraphQL\Language\AST\StringValueNode;
use App\GraphQL\Type\Scalar\AbstractScalarType;
use GraphQL\Utils\Utils;
use Validator;

class DatetimeType extends AbstractScalarType
{
    public $name = 'Datetime';
    public $description = 'The datetime scalar type represents date and time information. '
        . 'The datetime type uses Y-m-d H:i:s format.';

    /**
     * Serializes an internal value to include in a response.
     *
     * @param string $value
     * @return string
     */
    public function serialize($value)
    {
        // Assuming internal representation of email is always correct:
        return $value;

        // If it might be incorrect and you want to make sure that only correct values are included in response -
        // use following line instead:
        // return $this->parseValue($value);
    }

    /**
     * Parses an externally provided value (query variable) to use as an input
     *
     * @param mixed $value
     * @return mixed
     */
    public function parseValue($value)
    {
        $validator = Validator::make([$this->name => $value], [$this->name => 'date_format:"Y-m-d H:i:s"']);

        if ($validator->fails()) {
            $printSafe = Utils::printSafe($value);
            throw new \UnexpectedValueException(
                "Cannot represent value as datetime. Expected: Y-m-d H:i:s, but found: {$printSafe}."
            );
        }

        return $value;
    }

    /**
     * Parses an externally provided literal value (hardcoded in GraphQL query) to use as an input
     *
     * @param \GraphQL\Language\AST\Node $valueNode
     * @return string
     * @throws Error
     */
    public function parseLiteral($valueNode)
    {
        // Note: throwing GraphQL\Error\Error vs \UnexpectedValueException to benefit from GraphQL
        // error location in query:
        if (!$valueNode instanceof StringValueNode) {
            throw new Error('Query error: Can only parse strings got: ' . $valueNode->kind, [$valueNode]);
        }

        $validator = Validator::make([$this->name => $valueNode->value], [$this->name => 'date_format:"Y-m-d H:i:s"']);

        if ($validator->fails()) {
            throw new Error(
                "Not a valid datetime. Expected: Y-m-d H:i:s, but found: {$valueNode->value}.",
                [$valueNode]
            );
        }

        return $valueNode->value;
    }
}
