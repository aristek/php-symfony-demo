<?php

namespace App\Repository;

use App\Entity\User;
use App\Service\Synchronization\UserSynchronization;
use Aristek\Bundle\ExtraBundle\Model\UserInterface;
use Aristek\Bundle\SymfonyJSONAPIBundle\Repository\UserRepository as BaseUserRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

/**
 * Class UserRepository
 *
 * @method User create()
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 */
class UserRepository extends BaseUserRepository
{
    /**
     * @var UserSynchronization
     */
    private $userSynchronization;

    /**
     * UserRepository constructor.
     *
     * @param ManagerRegistry         $registry
     * @param UserSynchronization     $userSynchronization
     * @param EncoderFactoryInterface $encoderFactory
     */
    public function __construct(
        ManagerRegistry $registry,
        UserSynchronization $userSynchronization,
        EncoderFactoryInterface $encoderFactory
    ) {
        parent::__construct($registry, User::class);

        $this->userSynchronization = $userSynchronization;
        $this->encoderFactory = $encoderFactory;
    }

    /**
     * @param User|UserInterface $entity
     * @param bool               $flush
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
        } catch (\Throwable $throwable) {
            $this->_em->rollback();
            throw $throwable;
        }
    }
}
