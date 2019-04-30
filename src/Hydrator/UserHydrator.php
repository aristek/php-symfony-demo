<?php

namespace App\Hydrator;

use App\Entity\User;
use Aristek\Bundle\SymfonyJSONAPIBundle\JsonApi\Hydrator\AbstractHydrator;
use Aristek\Bundle\SymfonyJSONAPIBundle\JsonApi\Hydrator\HydratorPropertyAccessor;
use Aristek\Bundle\SymfonyJSONAPIBundle\Service\File\FileHandler;
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
     * @param ObjectManager            $objectManager
     * @param ProfileHydrator          $profileHydrator
     * @param UserRoleHydrator         $userRoleHydrator
     * @param HydratorPropertyAccessor $hydratorPropertyAccessor
     * @param FileHandler              $fileHandler
     */
    public function __construct(
        FileHandler $fileHandler,
        HydratorPropertyAccessor $hydratorPropertyAccessor,
        ObjectManager $objectManager,
        ProfileHydrator $profileHydrator,
        UserRoleHydrator $userRoleHydrator
    ) {
        parent::__construct($objectManager, $hydratorPropertyAccessor, $fileHandler);

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
