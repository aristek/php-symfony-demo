<?php

namespace Test\Integration\Controller\Resource;

use App\Entity\User;
use Aristek\Bundle\TestBundle\Test\DatabaseIntegrationTest;

/**
 * Class UserControllerTest
 */
class UserControllerTest extends DatabaseIntegrationTest
{
    /**
     * @var bool
     */
    protected $wrapTestInTransaction = true;

    /**
     * @var User[]
     */
    private $fixtures;

    /**
     * @test
     *
     * @return void
     */
    public function indexTest(): void
    {
        $this->client->request('GET', '/resources/users');

        $this->assertEquals(
            json_encode(
                [
                    'jsonapi' => ['version' => '1.0'],
                    'meta'    => ['pagination' => ['count' => 2]],
                    'links'   => [
                        'self'  => '/resources/users?page[offset]=0',
                        'first' => '/resources/users?page[offset]=0',
                        'last'  => '/resources/users?page[offset]=0',
                        'prev'  => null,
                        'next'  => null,
                    ],
                    'data'    => [
                        [
                            'type'       => 'users',
                            'id'         => (string) $this->fixtures['user_admin']->getId(),
                            'attributes' => [
                                'active'    => true,
                                'email'     => 'admin@aristek.test.com',
                                'firstName' => 'F_name',
                                'lastName'  => 'L_name',
                                'roles'     => ['ROLE_USER'],
                                'username'  => 'admin',
                            ],
                        ],
                        [
                            'type'       => 'users',
                            'id'         => (string) $this->fixtures['user_2']->getId(),
                            'attributes' => [
                                'active'    => true,
                                'email'     => 'user@aristek.test.com',
                                'firstName' => 'U_name',
                                'lastName'  => 'U_name',
                                'roles'     => ['ROLE_USER'],
                                'username'  => 'user',
                            ],
                        ],
                    ],
                ]
            ),
            $this->client->getResponse()->getContent()
        );
    }

    /**
     * @test
     *
     * @return void
     */
    public function newTest(): void
    {
    }

    /**
     * @test
     *
     * @return void
     */
    public function showTest(): void
    {
        foreach ($this->fixtures as $user) {
            $id = (string) $user->getId();
            $this->client->request('GET', sprintf('/resources/users/%s', $id));
            $this->assertEquals(
                json_encode(
                    [
                        'jsonapi' => ['version' => '1.0'],
                        'links'   => ['self' => sprintf('/users/%s', $id)],
                        'data'    => [
                            'type'       => 'users',
                            'id'         => $id,
                            'attributes' => [
                                'active'    => true,
                                'email'     => 'admin@aristek.test.com',
                                'firstName' => 'F_name',
                                'lastName'  => 'L_name',
                                'roles'     => ['ROLE_USER'],
                                'username'  => 'admin',
                            ],
                        ],
                    ]
                ),
                $this->client->getResponse()->getContent()
            );
        }
    }

    /**
     * @test
     *
     * @return void
     */
    public function editTest(): void
    {
    }

    /**
     * @test
     *
     * @return void
     */
    public function deleteTest(): void
    {
        foreach ($this->fixtures as $user) {
            $id = (string) $user->getId();
            $this->client->request('DELETE', sprintf('/resources/users/%s', $id));
            $this->assertEquals(204, $this->client->getResponse()->getStatusCode());
        }
    }

    /**
     * @test
     *
     * @return void
     */
    public function resetPasswordTest(): void
    {
    }

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->fixtures = $this->fixtureLoader->loadFixturesFromFiles(['Controller/UserController.yml']);
    }
}
