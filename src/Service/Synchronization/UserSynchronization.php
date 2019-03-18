<?php

namespace App\Service\Synchronization;

use App\Entity\User;
use Aristek\Bundle\ExtraBundle\Exception\AppException;

/**
 * Class UserSynchronization is used for sync User between micro services he has access to.
 */
class UserSynchronization
{
    /**
     * @param User $user
     *
     * @return void
     *
     * @throws AppException
     */
    public function synchronize(User $user): void
    {
        throw new AppException('Incomplete class.');
    }

    /**
     * @param User $user
     * @param int  $id
     *
     * @return void
     *
     * @throws AppException
     */
    public function delete(User $user, int $id): void
    {
        throw new AppException('Incomplete class.');
    }
}
