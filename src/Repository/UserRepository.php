<?php

namespace App\Repository;

use App\Entity\User;
use App\Service\Synchronization\UserSynchronization;
use Aristek\Bundle\SymfonyJSONAPIBundle\Repository\UserRepository as Base;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class UserRepository
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User create()
 */
class UserRepository extends Base
{
    /**
     * @var UserSynchronization
     */
    private $userSynchronization;

    /**
     * SettingRepository constructor.
     *
     * @param ManagerRegistry              $registry
     * @param UserSynchronization $userSynchronization
     */
    public function __construct(ManagerRegistry $registry, UserSynchronization $userSynchronization)
    {
        parent::__construct($registry, User::class);

        $this->userSynchronization = $userSynchronization;
    }

    /**
     * @param User $entity
     * @param bool $flush
     *
     * @return void
     */
    public function save($entity, $flush = true): void
    {
        parent::save($entity, $flush);
        $this->userSynchronization->synchronize($entity);
        // second saving of entity is for saving of synchronization errors
        parent::save($entity, $flush);
    }

    /**
     * @param User $entity
     * @param bool $flush
     *
     * @return void
     *
     * @throws \Throwable
     */
    public function remove($entity, $flush = true): void
    {
        try {
            $this->_em->beginTransaction();
            // method getId() would return null after remove
            $id = $entity->getId();

            parent::remove($entity);

            $this->userSynchronization->delete($entity, $id);
            $this->_em->commit();
        } catch (\Throwable $e) {
            $this->_em->rollback();
            throw $e;
        }
    }
}
