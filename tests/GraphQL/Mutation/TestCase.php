<?php
namespace Tests\GraphQL\Mutation;

use Tests\TestCase as BaseTestCase;
use GraphQL\Type\Definition\Type;

abstract class TestCase extends BaseTestCase
{
    use \Tests\GraphQL\GraphQL;

    public function getMutation()
    {
        $className = preg_replace('/Test$/', '', get_class($this));
        $className = preg_replace('/^Tests\\\GraphQL/', 'App\\\GraphQL', $className);
        return new $className;
    }

    public function testType()
    {
        $this->assertInstanceOf(
            Type::class,
            $this->getMutation()->type()
        );
    }

    public function testArgs()
    {
        $args = $this->getMutation()->args();
        $this->assertInternalType('array', $args);

        foreach ($args as $name => $data) {
            $message = '"' . $name . '" field definition should have';
            $this->assertArrayHasKey('type', $data, $message . ' a "type" key.');
            $this->assertArrayHasKey('description', $data, $message . ' a "description" key.');
            $this->assertNotNull($name, $message . ' value for "name" key.');
            $this->assertNotNull($data['type'], $message . ' value for "type" key.');
            $this->assertNotNull($data['description'], $message . ' value for "description" key.');
        }
    }

}
