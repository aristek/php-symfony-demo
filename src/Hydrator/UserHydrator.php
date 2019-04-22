<?php

namespace App\Hydrator;

use App\Entity\Profile;
use App\Entity\User;
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
     * UserHydrator constructor.
     *
     * @param ProfileHydrator $profileHydrator
     */
    public function __construct(
        ObjectManager $objectManager,
        WrongFieldsLogger $wrongFieldsLogger,
        ProfileHydrator $profileHydrator
    ) {
        parent::__construct($objectManager, $wrongFieldsLogger);

        $this->profileHydrator = $profileHydrator;
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
        $callback = function (Profile $profile) use ($user) {
            $user->setProfile($profile);
        };
        $this->hydrateChildEntity(
            $attributes,
            $callback,
            Profile::class,
            $this->profileHydrator,
            HydratorEntityRelationTypeEnum::TYPE_ONE
        );
    }
}
