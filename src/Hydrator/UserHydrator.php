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
    protected function getCommonAttributes(): array
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

}
