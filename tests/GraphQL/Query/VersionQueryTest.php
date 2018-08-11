<?php

namespace Tests\GraphQL\Query;

use Tests\GraphQL\Query\TestCase;

class VersionQueryTest extends TestCase
{

    public function testResolveReturnCurrentVersion()
    {
        $this->assertEquals(['version' => '1.0'], $this->getQuery()->resolve(null, null));
    }

    public function testShouldReturnVersionFromPublic()
    {
        $query = '
            query {
                currentVersion {
                    version
                }
            }
        ';

        $response = $this->graphql($query, 'public');

        $response->assertStatus(200)
            ->assertJsonFragment(['version' => '1.0']);

    }

    public function testShouldReturnTokenNotProvided()
    {
        $query = '
            query {
                currentVersion {
                    version
                }
            }
        ';

        $response = $this->graphql($query);

        $response->assertStatus(400)
            ->assertJsonFragment(['error' => 'token_not_provided']);

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
