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
     * @var RoleRepository
     */
    private $roleRepository;

    /**
     * UserRoleHydrator constructor.
     *
     * @param ObjectManager     $objectManager
     * @param WrongFieldsLogger $wrongFieldsLogger
     * @param RoleRepository    $roleRepository
     */
    public function __construct(
        ObjectManager $objectManager,
        WrongFieldsLogger $wrongFieldsLogger,
        RoleRepository $roleRepository
    ) {
        parent::__construct($objectManager, $wrongFieldsLogger);

        $this->roleRepository = $roleRepository;
    }

    /**
     * @return array
     */
    protected function getCommonAttributes(): array
    {
        return ['active', 'roleId'];
    }

    /**
     * @param UserRole $userRole
     * @param string   $roleId
     *
     * @return void
     */
    protected function hydrateRoleId(UserRole $userRole, string $roleId): void
    {
        $userRole->setRole($this->roleRepository->get($roleId));
    }
}
