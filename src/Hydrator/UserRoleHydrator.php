<?php

namespace App\Hydrator;

use App\Entity\UserRole;
use App\Repository\RoleRepository;
use Aristek\Bundle\SymfonyJSONAPIBundle\JsonApi\Hydrator\AbstractHydrator;
use Aristek\Bundle\SymfonyJSONAPIBundle\Service\WrongFieldsLogger;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class UserRoleHydrator
 */
class UserRoleHydrator extends AbstractHydrator
{
    /**
     * @var string
     */
    protected $acceptedType = 'userRoles';

    /**
     * @return array
     */
    protected function getCommonAttributes(): array
    {
        return ['active', 'role'];
    }
}
