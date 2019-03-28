<?php

namespace App\Hydrator;

use App\Entity\User;
use Aristek\Bundle\SymfonyJSONAPIBundle\JsonApi\Hydrator\AbstractHydrator;

/**
 * Class UserHydrator
 */
class UserHydrator extends AbstractHydrator
{
    /**
     * @var string
     */
    protected $acceptedType = 'users';

    /**
     * @return array
     */
    protected function getAttributes(): array
    {
        return [
            'username',
            'email',
            'firstName',
            'lastName',
            'password',
            'passwordChangeRequired',
            'active',
            'passwordChangeToken',
        ];
    }

    /**
     * @param User   $user
     * @param string $username
     *
     * @return void
     */
    protected function hydrateUsername(User $user, string $username): void
    {
        $user->setUsername($username);
    }

    /**
     * @param User   $user
     * @param string $email
     *
     * @return void
     */
    protected function hydrateEmail(User $user, string $email): void
    {
        $user->setEmail($email);
    }

    /**
     * @param User        $user
     * @param string|null $firstName
     *
     * @return void
     */
    protected function hydrateFirstName(User $user, ?string $firstName): void
    {
        $user->setFirstName($firstName);
    }

    /**
     * @param User        $user
     * @param string|null $lastName
     *
     * @return void
     */
    protected function hydrateLastName(User $user, ?string $lastName): void
    {
        $user->setLastName($lastName);
    }

    /**
     * @param User        $user
     * @param string|null $password
     *
     * @return void
     */
    protected function hydratePassword(User $user, ?string $password): void
    {
        if ($password) {
            $user->setPlainPassword($password);
        }
    }

    /**
     * @param User $user
     * @param bool $passwordChangeRequired
     *
     * @return void
     */
    protected function hydratePasswordChangeRequired(User $user, bool $passwordChangeRequired): void
    {
        $user->setPasswordChangeRequired($passwordChangeRequired);
    }

    /**
     * @param User $user
     * @param bool $active
     *
     * @return void
     */
    protected function hydrateActive(User $user, bool $active): void
    {
        $user->setActive($active);
    }

    /**
     * @param User        $user
     * @param string|null $passwordChangeToken
     *
     * @return void
     */
    protected function hydratePasswordChangeToken(User $user, ?string $passwordChangeToken): void
    {
        $user->setPasswordChangeToken($passwordChangeToken);
    }
}
