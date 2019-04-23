<?php

namespace App\Repository;

use App\Entity\Profile;
use Aristek\Bundle\ExtraBundle\ORM\AbstractServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class ProfileRepository
 */
class ProfileRepository extends AbstractServiceEntityRepository
{
    /**
     * ProfileRepository constructor.
     *
     * @param ManagerRegistry $registry
     * @param string          $entityClass
     */
    public function __construct(ManagerRegistry $registry, string $entityClass = Profile::class)
    {
        parent::__construct($registry, $entityClass);
    }
}
