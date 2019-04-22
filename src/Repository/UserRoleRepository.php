<?php

namespace App\Repository;

use App\Entity\UserRole;
use Aristek\Bundle\ExtraBundle\ORM\AbstractServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class UserRoleRepository
 */
class UserRoleRepository extends AbstractServiceEntityRepository
{
    /**
     * ProfileRepository constructor.
     *
     * @param ManagerRegistry $registry
     * @param string          $entityClass
     */
    public function __construct(ManagerRegistry $registry, string $entityClass = UserRole::class)
    {
        parent::__construct($registry, $entityClass);
    }
}
