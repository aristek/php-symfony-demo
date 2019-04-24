<?php

namespace App\Tests\Integration\Controller\Resource;

use App\Entity\User;
use App\Entity\UserRole;
use Aristek\Bundle\SymfonyJSONAPIBundle\Entity\File\File;
use Aristek\Bundle\SymfonyJSONAPIBundle\Test\AbstractControllerTest;

/**
 * Class UserControllerTest
 *
 * @method User getFirstDomainObjectFixture()
 */
class UserControllerTest extends AbstractControllerTest
{
    /**
     * @test
     *
     * @return void
     */
    public function resetPasswordTest(): void
    {
        $admin = $this->getFirstDomainObjectFixture();
        $oldPassword = $admin->getPassword();
        $this->client->request('GET', sprintf('/resources/users/%s/reset-password', $admin->getId()));
        $this->assertEquals(204, $this->client->getResponse()->getStatusCode());
        $this->assertNotEquals($oldPassword, $admin->getPassword());
    }

    /**
     * @return string
     */
    protected function getTableName(): string
    {
        return 'users';
    }

    /**
     * @return string
     */
    protected function getType(): string
    {
        return 'users';
    }

    /**
     * @return string
     */
    protected function getEndPoint(): string
    {
        return '/resources/users';
    }

    /**
     * @param string $fixtureName
     *
     * @return array
     */
    protected function getExpectedAttributes(string $fixtureName): array
    {
        $avatar1 = (string) $this->getIdentifier($this->fixtures['avatar_1']);
        $avatar2 = (string) $this->getIdentifier($this->fixtures['avatar_2']);
        $data = [
            'user_admin' => [
                'active'   => true,
                'avatar'   => [
                    'id'       => $avatar1,
                    'name'     => 'avatar.png',
                    'original' => sprintf('http://localhost/resources/users/%s.png', $avatar1),
                ],
                'email'    => 'admin@aristek.test.com',
                'username' => 'admin',
            ],
            'user_2'     => [
                'active'   => true,
                'avatar'   => [
                    'id'       => $avatar2,
                    'name'     => 'avatar2.png',
                    'original' => sprintf('http://localhost/resources/users/%s.png', $avatar2),
                ],
                'email'    => 'user@aristek.test.com',
                'username' => 'user',
            ],
        ];

        return $data[$fixtureName];
    }

    /**
     * @param string $fixtureName
     *
     * @return array
     */
    protected function getExpectedRelations(string $fixtureName): array
    {
        $data = [
            'user_admin' => [
                'profile'   => [
                    'data' => [
                        'type' => 'profiles',
                        'id'   => (string) $this->getIdentifier($this->fixtures['user_admin']),
                    ],
                ],
                'userRoles' => [
                    'data' => [
                        [
                            'type' => 'userRoles',
                            'id'   => (string) $this->getIdentifier($this->fixtures['user_admin_role']),
                        ],
                    ],
                ],
            ],
            'user_2'     => [
                'profile'   => [
                    'data' => [
                        'type' => 'profiles',
                        'id'   => (string) $this->getIdentifier($this->fixtures['user_2']),
                    ],
                ],
                'userRoles' => [
                    'data' => [
                        [
                            'type' => 'userRoles',
                            'id'   => (string) $this->getIdentifier($this->fixtures['user_2_role']),
                        ],
                    ],
                ],
            ],
        ];

        return $data[$fixtureName];
    }

    /**
     * @return array
     */
    protected function getNewRequestAttributes(): array
    {
        return [
            'email'               => 'email@email.com',
            'active'              => true,
            'username'            => 'username',
            'password'            => 'password',
            'profileId'           => null,
            'avatarData'          => [
                'name'     => 'avatar.png',
                'mimeType' => 'image/png',
                'content'  => base64_encode(file_get_contents(__DIR__.'/../../../../fixtures/files/avatar.png')),
                'size'     => 109,
            ],
            'profileAttributes'   => [
                'firstName' => 'firstName',
                'lastName'  => 'lastName',
            ],
            'userRoleIds'         => [null],
            'userRolesAttributes' => [
                [
                    'active'         => true,
                    'roleId'         => 'ROLE_USER',
                    'roleAttributes' => [
                        'code'        => 'ROLE_USER',
                        'description' => 'User',
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    protected function getNewExpectedAttributes(): array
    {
        $id = (string) $this->getLastIdByEntityName(File::class);

        return [
            'active'   => true,
            'avatar'   => [
                'id'       => $id,
                'name'     => 'avatar.png',
                'original' => sprintf('http://localhost/resources/users/%s.png', $id),
            ],
            'email'    => 'email@email.com',
            'username' => 'username',
        ];
    }

    /**
     * @return array
     */
    protected function getNewExpectedRelations(): array
    {
        return [
            'profile'   => [
                'data' => [
                    'type' => 'profiles',
                    'id'   => (string) $this->getLastIdByEntityName(User::class),
                ],
            ],
            'userRoles' => [
                'data' => [
                    [
                        'type' => 'userRoles',
                        'id'   => (string) $this->getLastIdByEntityName(UserRole::class),
                    ],
                ],
            ],
        ];
    }

    /**
     * @param User|object $domainObjectFixture
     *
     * @return array
     */
    protected function getEditRequestAttributes(object $domainObjectFixture): array
    {
        $identifier = (string) $this->getIdentifier($domainObjectFixture);

        return [
            'active'            => false,
            'email'             => 'admin2@aristek.test.com',
            'username'          => 'admin2',
            'profileId'         => $identifier,
            'profileAttributes' => [
                'id'        => $identifier,
                'firstName' => 'firstName',
                'lastName'  => 'lastName',
            ],
            // @todo: fix next cases
            // 'profileAttributes' => [
            //     1, 2, 3 // should not try to do invalid insert
            // ],
            // 'userRolesAttributes' => [ // should delete previous role from DB
            //     [
            //         'active'         => true,
            //         'roleId'         => 'ROLE_USER',
            //         'roleAttributes' => [
            //             'code'        => 'ROLE_USER',
            //             'description' => 'User',
            //         ],
            //     ],
            // ],
        ];
    }

    /**
     * @param User|object $domainObjectFixture
     *
     * @return array
     */
    protected function getEditExpectedAttributes(object $domainObjectFixture): array
    {
        $avatar1 = (string) $this->getIdentifier($this->fixtures['avatar_1']);

        return [
            'active'   => false,
            'avatar'   => [
                'id'       => $avatar1,
                'name'     => 'avatar.png',
                'original' => sprintf('http://localhost/resources/users/%s.png', $avatar1),
            ],
            'email'    => 'admin@aristek.test.com',
            'username' => 'admin',
        ];
    }

    /**
     * @param User|object $domainObjectFixture
     *
     * @return array
     */
    protected function getEditExpectedRelations(object $domainObjectFixture): array
    {
        return [
            'profile'   => [
                'data' => [
                    'type' => 'profiles',
                    'id'   => (string) $this->getIdentifier($this->fixtures['profile_admin']),
                ],
            ],
            'userRoles' => [
                'data' => [
                    [
                        'type' => 'userRoles',
                        'id'   => (string) $this->getIdentifier($this->fixtures['user_admin_role']),
                    ],
                ],
            ],
        ];
    }

    /**
     * @return string
     */
    protected function getDomainObjectClass(): string
    {
        return User::class;
    }
}
