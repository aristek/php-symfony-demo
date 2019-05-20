<?php

namespace App\Hydrator;

use App\Entity\User;
use Aristek\Bundle\JSONAPIBundle\Factory\EntityFactoryRegistry;
use Aristek\Bundle\JSONAPIBundle\Hydrator\AbstractHydrator;
use Aristek\Bundle\JSONAPIBundle\Hydrator\HydratorPropertyAccessor;
use Aristek\Bundle\JSONAPIBundle\Service\File\FileHandler;
use Aristek\Bundle\JSONAPIBundle\Service\ObjectManagerHelper;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class UserHydrator
 */
class UserHydrator extends AbstractHydrator
{
    /**
     * @var string
     */
    protected $acceptedType = 'users';

    /**
     * @var ProfileHydrator
     */
    private $profileHydrator;

    /**
     * @var UserRoleHydrator
     */
    private $userRoleHydrator;

    /**
     * UserHydrator constructor.
     *
     * @param EntityFactoryRegistry    $registry
     * @param FileHandler              $fileHandler
     * @param HydratorPropertyAccessor $hydratorPropertyAccessor
     * @param ObjectManager            $objectManager
     * @param ObjectManagerHelper      $objectManagerHelper
     * @param ProfileHydrator          $profileHydrator
     * @param UserRoleHydrator         $userRoleHydrator
     */
    public function __construct(
        EntityFactoryRegistry $registry,
        FileHandler $fileHandler,
        HydratorPropertyAccessor $hydratorPropertyAccessor,
        ObjectManager $objectManager,
        ObjectManagerHelper $objectManagerHelper,
        ProfileHydrator $profileHydrator,
        UserRoleHydrator $userRoleHydrator
    ) {
        parent::__construct($registry, $fileHandler, $hydratorPropertyAccessor, $objectManager, $objectManagerHelper);

        $this->profileHydrator = $profileHydrator;
        $this->userRoleHydrator = $userRoleHydrator;
    }

    /**
     * @return array
     */
    protected function getCreateAttributes(): array
    {
        return ['username', 'email'];
    }

    /**
     * @return array
     */
    protected function getCommonAttributes(): array
    {
        return ['avatar', 'password', 'active', 'profile', 'userRoles', 'departments'];
    }

    /**
     * @return AbstractHydrator[]
     */
    protected function getChildrenHydrators(): array
    {
        return [
            'profile'   => $this->profileHydrator,
            'userRoles' => $this->userRoleHydrator,
        ];
    }

    /**
     * @param User        $user
     * @param string|null $password
     *
     * @return void
     */
    protected function hydratePassword(User $user, ?string $password): void
    {
        if ($password) {
            $user->setPlainPassword($password);
        }
    }
}
