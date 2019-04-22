<?php

namespace App\Hydrator;

use App\Entity\Profile;
use App\Entity\User;
use App\Entity\UserRole;
use Aristek\Bundle\SymfonyJSONAPIBundle\Enum\HydratorEntityRelationTypeEnum;
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
            'password',
            'passwordChangeRequired',
            'active',
            'passwordChangeToken',
            'profileAttributes',
            'userRolesAttributes',
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

    /**
     * @param User  $user
     * @param array $attributes
     *
     * @return void
     */
    protected function hydrateProfileAttributes(User $user, array $attributes = []): void
    {
        $this->hydrateChildEntity(
            $attributes,
            [$user, 'setProfile'],
            Profile::class,
            $this->profileHydrator,
            HydratorEntityRelationTypeEnum::TYPE_ONE
        );
    }

    /**
     * @param User  $user
     * @param array $attributes
     *
     * @return void
     */
    protected function hydrateUserRolesAttributes(User $user, array $attributes = []): void
    {
        $this->hydrateChildEntity(
            $attributes,
            [$user, 'setUserRoles'],
            UserRole::class,
            $this->userRoleHydrator,
            HydratorEntityRelationTypeEnum::TYPE_MANY
        );
    }
}
