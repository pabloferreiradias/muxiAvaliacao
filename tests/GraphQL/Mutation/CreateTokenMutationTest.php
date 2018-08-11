<?php

namespace Tests\GraphQL\Mutation;

use App\Models\User;
use App\GraphQL\Mutation\CreateTokenMutation;
use JWTAuth;

class CreateTokenMutationTest extends TestCase
{

    public function testResolveGenerateValidJwtToken()
    {
        $user = factory(User::class)->create([
            'login' => 'my_login',
            'password' => bcrypt('my_password')
        ]);

        $credentials = [
            'login' => $user->login,
            'password' => 'my_password'
        ];

        $response = $this->getMutation()->resolve(null, $credentials);
        $this->assertNotEmpty($response['token']);
    }

    /**
     * @expectedException        \Symfony\Component\HttpKernel\Exception\HttpException
     * @expectedExceptionStatusCode    401
     * @expectedExceptionMessage Invalid login or password.
     */
    public function testResolveAbortWithError401WhenInvalidLoginOrPassword()
    {
        $user = factory(User::class)->create([
            'login' => 'my_login',
            'password' => bcrypt('my_password')
        ]);

        $credentials = [
            'login' => $user->login,
            'password' => 'my_password2'
        ];

        $this->getMutation()->resolve(null, $credentials);
    }

    /**
     * @expectedException        \Symfony\Component\HttpKernel\Exception\HttpException
     * @expectedExceptionStatusCode    500
     * @expectedExceptionMessage Could not create token.
     */
    public function testResolveThrowUnexpectedException()
    {
        $exception = new \Tymon\JWTAuth\Exceptions\JWTException();
        JWTAuth::shouldReceive('attempt')->andThrow($exception);

        $credentials = [
            'login' => 'my_login',
            'password' => 'my_password2'
        ];

        $this->getMutation()->resolve(null, $credentials);
    }

    public function testShouldCreateTokenWhenUsernameAndPasswordAreValid()
    {
        $user = factory(User::class)->create([
            'login' => 'my_login',
            'password' => bcrypt('my_password')
        ]);

        $variables = [
            'login' => $user->login,
            'password' => 'my_password'
        ];

        $mutation = '
            mutation createToken($login: String!, $password: String!){
                createToken(login: $login, password: $password) {
                    token
                }
            }
        ';

        $response = $this->graphql($mutation, 'public', $variables);
        $json = $response->json();

        $response->assertStatus(200);
        $this->assertNotEmpty($json['data']['createToken']['token']);
    }

    public function testShouldReturnErrorBecauseUsernameOrPasswordAreNotValid()
    {
        $user = factory(User::class)->create([
            'login' => 'my_login',
            'password' => bcrypt('my_password')
        ]);

        $variables = [
            'login' => $user->login,
            'password' => 'password_wrong'
        ];

        $mutation = '
            mutation createToken($login: String!, $password: String!){
                createToken(login: $login, password: $password) {
                    token
                }
            }
        ';

        $response = $this->graphql($mutation, 'public', $variables);
        $json = $response->json();

        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'Invalid login or password.']);

        $this->assertNull($json['data']['createToken']['token']);
    }
}
