<?php

namespace Tests\GraphQL\Query;

use Tests\GraphQL\Query\TestCase;

class UsersQueryTest extends TestCase
{

    public function testShouldBeAbleToFilterAnUser()
    {
        $authenticatedToken = $this->createBearerToken();
        $existingUser = $this->_user;
        foreach(['id: ' . $existingUser->id, 'login: "' . $existingUser->login . '"'] as $filter) {
            $query = "
                query{
                  users($filter){
                    total
                    data{
                      login
                    }
                  }
                }
            ";

            $response = $this->withHeaders(['Authorization' => 'Bearer ' . $authenticatedToken])->graphql($query);

            $response->assertStatus(200)
                ->assertJsonFragment(['login' => $existingUser->login]);
        }
        foreach(['id: ' . ($existingUser->id+1), 'login: "Wrong' . $existingUser->login . '"'] as $filter) {
            $query = "
                query{
                  users($filter){
                    total
                    data{
                      login
                    }
                  }
                }
            ";

            $response = $this->withHeaders(['Authorization' => 'Bearer ' . $authenticatedToken])->graphql($query);

            $response->assertStatus(200)
                ->assertJsonFragment(['data' => [] ]);
        }


    }

    public function testShouldReturnUserList()
    {
        $authenticatedToken = $this->createBearerToken();
        $existingUser = $this->_user;
        $query = '
            query{
              users{
                total
                data{
                  login
                }
              }
            }
        ';

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $authenticatedToken])->graphql($query);

        $response->assertStatus(200)
            ->assertJsonFragment(['login' => $existingUser->login]);

    }

    public function testShouldReturnCurrentVersionPassingAValidJWT()
    {
        $authenticatedToken = $this->createBearerToken();

        $query = '
            query {
                currentVersion {
                    version
                }
            }
        ';

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $authenticatedToken])->graphql($query);
        $response->assertStatus(200)
            ->assertJsonFragment(['version' => '1.0']);
    }

    public function testShouldReturnFailureWhenNotProvidedAJWTToken()
    {
        $query = '
            query {
                currentVersion {
                    version
                }
            }
        ';

        $response = $this->withHeaders(['Authorization' => 'Bearer IsNotAJWTToken'])->graphql($query);
        $response->assertStatus(400)
            ->assertJsonFragment(['error' => 'token_not_provided']);
    }
}
