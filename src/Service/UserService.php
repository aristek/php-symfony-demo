<?php

namespace App\Service;

use Aristek\Bundle\ExtraBundle\Exception\AppException;
use Aristek\Bundle\SymfonyJSONAPIBundle\API\APIUserData;
use Aristek\Bundle\SymfonyJSONAPIBundle\Model\UserInterface;
use Aristek\Bundle\SymfonyJSONAPIBundle\Service\User\UserServiceInterface;

/**
 * Class UserService
 */
class UserService implements UserServiceInterface
{
    /**
     * @param string $username
     * @param string $password
     *
     * @return void
     *
     * @throws AppException
     */
    public function login(string $username, string $password): void
    {
        throw new AppException('Incomplete class.');
    }

    /**
     * @param APIUserData $data
     *
     * @return void
     *
     * @throws \LogicException
     *
     * @deprecated since 11/16/17
     */
    public function authenticate(APIUserData $data): void
    {
        throw new \LogicException('This method is deprecated.');
    }

    /**
     * @param UserInterface $user
     *
     * @return void
     *
     * @throws AppException
     */
    public function authenticateUser(UserInterface $user): void
    {
        throw new AppException('Incomplete class.');
    }

    /**
     * @param int    $id
     * @param string $oldPassword
     * @param string $newPassword
     *
     * @return void
     *
     * @throws AppException
     */
    public function changePasswordById(int $id, string $oldPassword, string $newPassword): void
    {
        throw new AppException('Incomplete class.');
    }

    /**
     * @param UserInterface $user
     * @param string        $oldPassword
     * @param string        $newPassword
     *
     * @return void
     *
     * @throws AppException
     */
    public function changePassword(UserInterface $user, string $oldPassword, string $newPassword): void
    {
        throw new AppException('Incomplete class.');
    }

    /**
     * @param UserInterface $user
     * @param string        $password
     *
     * @return void
     *
     * @throws AppException
     */
    public function updateUserPassword(UserInterface $user, string $password): void
    {
        throw new AppException('Incomplete class.');
    }

    /**
     * @param string $email
     *
     * @return void
     *
     * @throws AppException
     */
    public function forgetPassword(string $email): void
    {
        throw new AppException('Incomplete class.');
    }

    /**
     * @param int $id
     *
     * @return void
     *
     * @throws AppException
     */
    public function resetPasswordById(int $id): void
    {
        throw new AppException('Incomplete class.');
    }

    /**
     * @param UserInterface $user
     *
     * @return void
     *
     * @throws AppException
     */
    public function resetPassword(UserInterface $user): void
    {
        throw new AppException('Incomplete class.');
    }

    /**
     * @param string $username
     * @param string $password
     * @param array  $roles
     * @param string $email
     *
     * @return UserInterface
     *
     * @throws AppException
     */
    public function createOrUpdateUser(
        string $username,
        string $password,
        array $roles,
        string $email = ''
    ): UserInterface {
        throw new AppException('Incomplete class.');
    }

    /**
     * @param UserInterface $user
     *
     * @return void
     *
     * @throws AppException
     */
    public function save(UserInterface $user): void
    {
        throw new AppException('Incomplete class.');
    }
}
