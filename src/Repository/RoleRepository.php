<?php

namespace App\Repository;

use App\Entity\Role;
use Aristek\Bundle\ExtraBundle\ORM\AbstractServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class RoleRepository
 */
class RoleRepository extends AbstractServiceEntityRepository
{
    /**
     * ProfileRepository constructor.
     *
     * @param ManagerRegistry $registry
     * @param string          $entityClass
     */
    public function __construct(ManagerRegistry $registry, string $entityClass = Role::class)
    {
        parent::__construct($registry, $entityClass);
    }
}
