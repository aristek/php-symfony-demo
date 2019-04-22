<?php

namespace App\Hydrator;

use App\Entity\User;
use Aristek\Bundle\SymfonyJSONAPIBundle\JsonApi\Hydrator\AbstractHydrator;
use Aristek\Bundle\SymfonyJSONAPIBundle\Service\File\NewFileService;

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
