<?php

namespace App\Service\Synchronization;

use App\Entity\User;

/**
 * Class UserSynchronization is used for sync User between micro services he has access to.
 */
class UserSynchronization
{
    /**
     * @param User $user
     *
     * @return void
     */
    public function synchronize(User $user): void
    {
    }

    /**
     * @param User $user
     * @param int  $id
     *
     * @return void
     */
    public function delete(User $user, int $id): void
    {
    }
}
