<?php

namespace App\Repository;

use App\Entity\Department;
use Aristek\Bundle\ExtraBundle\ORM\AbstractServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class DepartmentRepository
 */
class DepartmentRepository extends AbstractServiceEntityRepository
{
    /**
     * ProfileRepository constructor.
     *
     * @param ManagerRegistry $registry
     * @param string          $entityClass
     */
    public function __construct(ManagerRegistry $registry, string $entityClass = Department::class)
    {
        parent::__construct($registry, $entityClass);
    }
}
