<?php

namespace App\Factory;

use App\Entity\User;
use Aristek\Bundle\JSONAPIBundle\Factory\EntityFactoryInterface;

/**
 * Class UserFactory
 */
class UserFactory implements EntityFactoryInterface
{
    /**
     * @return User
     */
    public function create(): object
    {
        return new User();
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return User::class;
    }
}
