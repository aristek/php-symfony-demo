<?php

namespace App\Hydrator;

use App\Entity\User;
use Aristek\Bundle\SymfonyJSONAPIBundle\JsonApi\Hydrator\AbstractHydrator;
use Aristek\Bundle\SymfonyJSONAPIBundle\Service\File\NewFileService;
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
     * @var NewFileService
     */
    private $newFileService;

    /**
     * UserHydrator constructor.
     *
     * @param ObjectManager     $objectManager
     * @param WrongFieldsLogger $wrongFieldsLogger
     * @param NewFileService    $newFileService
     */
    public function __construct(
        ObjectManager $objectManager,
        WrongFieldsLogger $wrongFieldsLogger,
        NewFileService $newFileService
    ) {
        parent::__construct($objectManager, $wrongFieldsLogger);

        $this->newFileService = $newFileService;
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
            //            'firstName',
            //            'lastName',
            'password',
            'passwordChangeRequired',
            'active',
            'passwordChangeToken',
        ];
    }

    /**
     * @param User  $user
     * @param array $avatar
     *
     * @return void
     */
    public function hydrateAvatar(User $user, array $avatar = []): void
    {
        $this->newFileService->setDataToField($user, 'avatar', $avatar);
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
     * @return array
     */
    protected function getAttributes(): array
    {
        return $this->getCommonAttributes();
    }
}
