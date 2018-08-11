<?php

namespace Tests\GraphQL\Type\Scalar;

use Tests\TestCase;
use App\GraphQL\Type\Scalar\DatetimeType;
use GraphQL\Language\AST\StringValueNode;
use GraphQL\Language\AST\IntValueNode;

class DatetimeTypeTest extends TestCase
{
    protected $datetimeType;

    public function setUp()
    {
        parent::setUp();
        $this->datetimeType = new DatetimeType;
    }

    public function testSerializeReturnSameValue()
    {
        $this->assertEquals('abc', $this->datetimeType->serialize('abc'));
    }

    public function testParseValueReturnValueWhenDatetimeIsInValidFormatYmdHis()
    {
        $value = '2017-10-15 10:39:10';
        $this->assertEquals($value, $this->datetimeType->parseValue($value));
    }

    /**
     * @expectedException \UnexpectedValueException
     */
    public function testParseValueThrowExceptionWhenDatetimeNotIsInValidFormatYmdHis()
    {
        $value = '2017-10-15';
        $this->assertEquals($value, $this->datetimeType->parseValue($value));
    }

    public function testParseLiteralReturnNodeValueWhenDatetimeIsInValidFormatYmdHis()
    {
        $node = new StringValueNode(['value' => '2017-10-15 10:39:10']);
        $this->assertEquals($node->value, $this->datetimeType->parseLiteral($node));
    }

    /**
     * @expectedException \Exception
     */
    public function testParseLiteralThrowExceptionWhenNodeValueNotString()
    {
        $node = new IntValueNode(['value' => '2017-10-15 10:39:10']);
        $this->datetimeType->parseLiteral($node);
    }

    /**
     * @expectedException \Exception
     */
    public function testParseLiteralThrowExceptionWhenDatetimeNotIsInValidFormatYmdHis()
    {
        $node = new StringValueNode(['value' => '2017-10-15']);
        $this->datetimeType->parseLiteral($node);
    }
}
