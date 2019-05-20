<?php

namespace App\Tests\Integration\Controller\Resource;

use App\Entity\User;
use App\Entity\UserRole;
use Aristek\Bundle\JSONAPIBundle\Entity\File\File;
use Aristek\Bundle\JSONAPIBundle\Test\AbstractControllerTest;

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
     * @return array
     */
    protected function getIncludeParams(): array
    {
        return ['profile.contacts'];
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
                'profile'  => [
                    'firstName' => 'F_name',
                    'lastName'  => 'L_name',
                ],
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
                'profile'  => [
                    'firstName' => 'U_name',
                    'lastName'  => 'U_name',
                ],
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
                'departments' => [
                    'data' => [
                        [
                            'type' => 'departments',
                            'id'   => (string) $this->getIdentifier($this->fixtures['dev']),
                        ],
                        [
                            'type' => 'departments',
                            'id'   => (string) $this->getIdentifier($this->fixtures['spt']),
                        ],
                    ],
                ],
                'userRoles'   => [
                    'data' => [
                        [
                            'type' => 'userRoles',
                            'id'   => (string) $this->getIdentifier($this->fixtures['user_admin_role']),
                        ],
                    ],
                ],
                'profile'     => [
                    'contacts' => [
                        'data' => [
                            [
                                'type' => 'contacts',
                                'id'   => (string) $this->getIdentifier($this->fixtures['admin_work']),
                            ],
                            [
                                'type' => 'contacts',
                                'id'   => (string) $this->getIdentifier($this->fixtures['admin_home']),
                            ],
                        ],
                    ],
                ],
            ],
            'user_2'     => [
                'departments' => [
                    'data' => [
                        [
                            'type' => 'departments',
                            'id'   => (string) $this->getIdentifier($this->fixtures['hr']),
                        ],
                        [
                            'type' => 'departments',
                            'id'   => (string) $this->getIdentifier($this->fixtures['sale']),
                        ],
                    ],
                ],
                'userRoles'   => [
                    'data' => [
                        [
                            'type' => 'userRoles',
                            'id'   => (string) $this->getIdentifier($this->fixtures['user_2_role']),
                        ],
                    ],
                ],
                'profile'     => [
                    'contacts' => [
                        'data' => [
                            [
                                'type' => 'contacts',
                                'id'   => (string) $this->getIdentifier($this->fixtures['user_2_work']),
                            ],
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
    protected function getIncluded(): array
    {
        return [
            [
                'type'       => 'contacts',
                'id'         => (string) $this->getIdentifier($this->fixtures['admin_work']),
                'attributes' => [
                    'phone' => '+375 (00) 000-00-00',
                    'email' => 'admin@work.com',
                ],
            ],
            [
                'type'       => 'contacts',
                'id'         => (string) $this->getIdentifier($this->fixtures['admin_home']),
                'attributes' => [
                    'phone' => '+375 (00) 000-00-01',
                    'email' => 'admin@home.com',
                ],
            ],
            [
                'type'       => 'contacts',
                'id'         => (string) $this->getIdentifier($this->fixtures['user_2_work']),
                'attributes' => [
                    'phone' => '+375 (00) 000-00-02',
                    'email' => 'user@work.com',
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    protected function getNewRequestAttributes(): array
    {
        return [
            'email'                 => 'email@email.com',
            'active'                => true,
            'username'              => 'username',
            'password'              => 'password',
            'profileId'             => null,
            'avatarData'            => [
                'name'     => 'avatar.png',
                'mimeType' => 'image/png',
                'content'  => base64_encode(file_get_contents(__DIR__.'/../../../../fixtures/files/avatar.png')),
                'size'     => 109,
            ],
            'profileAttributes'     => [
                'firstName' => 'firstName',
                'lastName'  => 'lastName',
            ],
            'userRoleIds'           => [null],
            'userRolesAttributes'   => [
                [
                    'active'         => false,
                    'roleId'         => 'ROLE_USER',
                    'roleAttributes' => [
                        'id'          => 'ROLE_USER',
                        'description' => 'User',
                    ],
                ],
            ],
            'departmentIds'         => [(string) $this->getIdentifier($this->fixtures['spt'])],
            'departmentsAttributes' => [],
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
            'profile'  => [
                'firstName' => 'firstName',
                'lastName'  => 'lastName',
            ],
        ];
    }

    /**
     * @return array
     */
    protected function getNewExpectedRelations(): array
    {
        return [
            'departments' => [
                'data' => [
                    [
                        'type' => 'departments',
                        'id'   => (string) $this->getIdentifier($this->fixtures['spt']),
                    ],
                ],
            ],
            'userRoles'   => [
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
            'active'                => false,
            'email'                 => 'admin2@aristek.test.com',
            'username'              => 'admin2',
            'profileId'             => $identifier,
            'profileAttributes'     => [
                'id'        => $identifier,
                'firstName' => 'firstName',
                'lastName'  => 'lastName',
            ],
            'userRolesAttributes'   => [
                [
                    'active'         => true,
                    'roleId'         => 'ROLE_USER',
                    'roleAttributes' => [
                        'id'          => 'ROLE_USER',
                        'description' => 'User',
                    ],
                ],
            ],
            'departmentIds'         => [(string) $this->getIdentifier($this->fixtures['sale'])],
            'departmentsAttributes' => [],
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
            'profile'  => [
                'firstName' => 'firstName',
                'lastName'  => 'lastName',
            ],
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
            'departments' => [
                'data' => [
                    [
                        'type' => 'departments',
                        'id'   => (string) $this->getIdentifier($this->fixtures['sale']),
                    ],
                ],
            ],
            'userRoles'   => [
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
     * @return string
     */
    protected function getDomainObjectClass(): string
    {
        return User::class;
    }
}
