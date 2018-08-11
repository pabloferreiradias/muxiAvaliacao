<?php
namespace Tests\GraphQL\Query;

use Tests\TestCase as BaseTestCase;
use GraphQL\Type\Definition\Type;
use App\Models\User;

abstract class TestCase extends BaseTestCase
{
    use \Tests\GraphQL\GraphQL;


    public $_token;
    public $_user;

    public function getQuery()
    {
        $className = preg_replace('/Test$/', '', get_class($this));
        $className = preg_replace('/^Tests\\\GraphQL/', 'App\\\GraphQL', $className);
        return new $className;
    }

    public function createBearerToken()
    {
        if(!empty($this->_token)){
            return $this->_token;
        }
        $this->_user = factory(User::class)->create([
            'login' => 'my_login',
            'password' => bcrypt('my_password')
        ]);

        $variables = [
            'login' => $this->_user->login,
            'password' => 'my_password'
        ];

        $mutation = '
            mutation createToken($login: String!, $password: String!){
                createToken(login: $login, password: $password) {
                    token
                }
            }
        ';

        $tokenResponse = $this->graphql($mutation, 'public', $variables);
        $this->_token = $tokenResponse->json()['data']['createToken']['token'];
        return $this->_token;
    }
 
    public function testType()
    {
        $this->assertInstanceOf(
            Type::class,
            $this->getQuery()->type()
        );
    }

    public function testArgs()
    {
        $args = $this->getQuery()->args();
        $this->assertInternalType('array', $args);

        foreach ($args as $name => $data) {
            $message = '"' . $name . '" field definition should have';
            $this->assertArrayHasKey('name', $data, $message . ' a "name" key.');
            $this->assertArrayHasKey('type', $data, $message . ' a "type" key.');
            $this->assertArrayHasKey('description', $data, $message . ' a "description" key.');
            $this->assertNotNull($data['name'], $message . ' value for "name" key.');
            $this->assertNotNull($data['type'], $message . ' value for "type" key.');
            $this->assertNotNull($data['description'], $message . ' value for "description" key.');
        }
    }

}
