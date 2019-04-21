<?php

namespace App\Tests\Integration\Controller\Resource;

use App\Entity\User;
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
        return 'user';
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
        $data = [
            'user_admin' => [
                'active'            => true,
                'email'             => 'admin@aristek.test.com',
                'roles'             => ['ROLE_ADMIN'],
                'username'          => 'admin',
                'profileAttributes' => [
                    'firstName' => 'F_name',
                    'lastName'  => 'L_name',
                ],
            ],
            'user_2'     => [
                'active'            => true,
                'email'             => 'user@aristek.test.com',
                'roles'             => ['ROLE_USER'],
                'username'          => 'user',
                'profileAttributes' => [
                    'firstName' => 'U_name',
                    'lastName'  => 'U_name',
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
            'email'     => 'email@email.com',
            'username'  => 'username',
            'password'  => 'password',
            'roles'     => ['ROLE_USER'],
            'active'    => true,
            'firstName' => 'firstName',
            'lastName'  => 'lastName',
        ];
    }

    /**
     * @return array
     */
    protected function getNewExpectedAttributes(): array
    {
        return [
            'active'    => true,
            'email'     => 'email@email.com',
            'firstName' => 'firstName',
            'lastName'  => 'lastName',
            'roles'     => ['ROLE_USER'],
            'username'  => 'username',
        ];
    }

    /**
     * @return array
     */
    protected function getEditRequestAttributes(): array
    {
        return [
            'active'    => false,
            'firstName' => 'firstName',
            'lastName'  => 'lastName',
        ];
    }

    /**
     * @return array
     */
    protected function getEditExpectedAttributes(): array
    {
        return [
            'active'    => false,
            'email'     => 'admin@aristek.test.com',
            'firstName' => 'firstName',
            'lastName'  => 'lastName',
            'roles'     => ['ROLE_USER'],
            'username'  => 'admin',
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
