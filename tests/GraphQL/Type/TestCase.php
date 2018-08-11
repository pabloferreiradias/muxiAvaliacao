<?php
namespace Tests\GraphQL\Type;

use Tests\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    public function testFields()
    {
        $fields = $this->getType()->fields();

        $this->assertNotEquals(
            0,
            count($fields),
            'fields should have at least one field defined'
        );

        foreach ($fields as $name => $data) {
            $message = '"' . $name . '" field definition should have';
            $this->assertArrayHasKey('type', $data, $message . ' a "type" key.');
            $this->assertArrayHasKey('description', $data, $message . ' a "description" key.');
            $this->assertNotNull($data['type'], $message . ' value for "type" key.');
            $this->assertNotNull($data['description'], $message . ' value for "description" key.');
        }
    }


    public function getType()
    {
        $className = preg_replace('/Test$/', '', get_class($this));
        $className = preg_replace('/^Tests\\\GraphQL/', 'App\\\GraphQL', $className);
        return new $className;
    }



}
