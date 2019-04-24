<?php

namespace App\Hydrator;

use App\Entity\User;
use Aristek\Bundle\SymfonyJSONAPIBundle\JsonApi\Hydrator\AbstractHydrator;
use Aristek\Bundle\SymfonyJSONAPIBundle\Service\WrongFieldsLogger;
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
     * @param ObjectManager     $objectManager
     * @param WrongFieldsLogger $wrongFieldsLogger
     * @param ProfileHydrator   $profileHydrator
     * @param UserRoleHydrator  $userRoleHydrator
     */
    public function __construct(
        ObjectManager $objectManager,
        WrongFieldsLogger $wrongFieldsLogger,
        ProfileHydrator $profileHydrator,
        UserRoleHydrator $userRoleHydrator
    ) {
        parent::__construct($objectManager, $wrongFieldsLogger);

        $this->profileHydrator = $profileHydrator;
        $this->userRoleHydrator = $userRoleHydrator;
    }

    /**
     * @return array
     */
    protected function getCommonAttributes(): array
    {
        return [
            'username',
            'email',
            'avatar',
            'password',
            'passwordChangeRequired',
            'active',
            'passwordChangeToken',
            'profile',
            'userRoles',
        ];
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
