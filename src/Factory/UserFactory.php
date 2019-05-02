<?php

namespace App\Factory;

use App\Entity\User;
use Aristek\Bundle\SymfonyJSONAPIBundle\Factory\EntityFactoryInterface;
use Aristek\Bundle\SymfonyJSONAPIBundle\Factory\EntityInterface;

/**
 * Class UserFactory
 */
class UserFactory implements EntityFactoryInterface
{
    /**
     * @return EntityInterface|User
     */
    public function create(): EntityInterface
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
