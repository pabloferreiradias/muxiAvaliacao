<?php

namespace Tests\GraphQL\Type\Scalar;

use Tests\TestCase;
use App\GraphQL\Type\Scalar\DateType;
use GraphQL\Language\AST\StringValueNode;
use GraphQL\Language\AST\IntValueNode;

class DateTypeTest extends TestCase
{
    protected $dateType;

    public function setUp()
    {
        parent::setUp();
        $this->dateType = new DateType;
    }

    public function testSerializeReturnSameValue()
    {
        $this->assertEquals('abc', $this->dateType->serialize('abc'));
    }

    public function testParseValueReturnValueWhenDatetimeIsInValidFormatYmdHis()
    {
        $value = '2017-10-15';
        $this->assertEquals($value, $this->dateType->parseValue($value));
    }

    /**
     * @expectedException \UnexpectedValueException
     */
    public function testParseValueThrowExceptionWhenDatetimeNotIsInValidFormatYmdHis()
    {
        $value = '02/04/2017';
        $this->assertEquals($value, $this->dateType->parseValue($value));
    }

    public function testParseLiteralReturnNodeValueWhenDatetimeIsInValidFormatYmdHis()
    {
        $node = new StringValueNode(['value' => '2017-10-15']);
        $this->assertEquals($node->value, $this->dateType->parseLiteral($node));
    }

    /**
     * @expectedException \Exception
     */
    public function testParseLiteralThrowExceptionWhenNodeValueNotString()
    {
        $node = new IntValueNode(['value' => '2017-10-15 10:39:10']);
        $this->dateType->parseLiteral($node);
    }

    /**
     * @expectedException \Exception
     */
    public function testParseLiteralThrowExceptionWhenDatetimeNotIsInValidFormatYmdHis()
    {
        $node = new StringValueNode(['value' => '15/10/2016']);
        $this->dateType->parseLiteral($node);
    }
}
