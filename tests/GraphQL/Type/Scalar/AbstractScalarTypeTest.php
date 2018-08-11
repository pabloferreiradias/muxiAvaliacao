<?php

namespace Tests\GraphQL\Type\Scalar;

use Tests\TestCase;
use App\GraphQL\Type\Scalar\AbstractScalarType;
use Closure;

class AbstractScalarTypeTest extends TestCase
{
    protected $abstractClass;

    public function setUp()
    {
        $this->abstractClass = new class extends AbstractScalarType {
            public $name = 'Abc';
            public $description = 'My abc';
            
            public function serialize($value)
            {
                return $value;
            }

            public function parseValue($value)
            {
                return $value;
            }

            public function parseLiteral($valueNode)
            {
                return $valueNode;
            }
        };
    }

    public function testToArray()
    {
        $esperado = [
            'name' => $this->abstractClass->name,
            'description' => $this->abstractClass->description,
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

        $this->assertEquals(
            $esperado,
            $this->abstractClass->toArray()
        );
    }

    public function testToArraySerialize()
    {
        $toArray = $this->abstractClass->toArray();
        $closure = $toArray['serialize'];

        $this->assertInstanceOf(Closure::class, $closure);
        $this->assertEquals($this->abstractClass, $closure($this->abstractClass));
    }

    public function testToArrayParseValue()
    {
        $toArray = $this->abstractClass->toArray();
        $closure = $toArray['parseValue'];

        $this->assertInstanceOf(Closure::class, $closure);
        $this->assertEquals('2017-10-15', $closure('2017-10-15'));
    }

    public function testToArrayParseLiteral()
    {
        $toArray = $this->abstractClass->toArray();
        $closure = $toArray['parseLiteral'];

        $this->assertInstanceOf(Closure::class, $closure);
        $this->assertEquals('2017-10-12', $closure('2017-10-12'));
    }

    public function testToType()
    {
        $toType = $this->abstractClass->toType();

        $this->assertInstanceOf(AbstractScalarType::class, $toType);
        $this->assertEquals($this->abstractClass->name, $toType->name);
        $this->assertEquals($this->abstractClass->description, $toType->description);
    }
}
