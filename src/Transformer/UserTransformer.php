<?php

namespace App\Transformer;

use App\Entity\User;
use Aristek\Bundle\SymfonyJSONAPIBundle\JsonApi\Transformer\AbstractTransformer;

/**
 * Class UserTransformer
 */
class UserTransformer extends AbstractTransformer
{
    /**
     * @param User $user
     *
     * @return string
     */
    public function getType($user): string
    {
        return 'users';
    }

    /**
     * @param User $user
     *
     * @return string
     */
    public function getId($user): string
    {
        return (string) $user->getId();
    }

    /**
     * @return array
     */
    protected function getTransformableAttributes(): array
    {
        return [
            'active',
            'email',
            'firstName',
            'lastName',
            'roles',
            'username',
        ];
    }
}
