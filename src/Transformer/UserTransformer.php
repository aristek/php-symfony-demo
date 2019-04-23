<?php

namespace App\Transformer;

use App\Entity\User;
use Aristek\Bundle\SymfonyJSONAPIBundle\JsonApi\Transformer\AbstractTransformer;
use WoohooLabs\Yin\JsonApi\Schema\Relationship\ToManyRelationship;
use Aristek\Bundle\SymfonyJSONAPIBundle\Service\File\NewFileService;
use WoohooLabs\Yin\JsonApi\Schema\Relationship\ToOneRelationship;

/**
 * Class UserTransformer
 */
class UserTransformer extends AbstractTransformer
{
    /**
     * @var FileTransformer
     */
    private $fileTransformer;

    /**
     * @var NewFileService
     */
    private $newFileService;

    /**
     * @var ProfileTransformer
     */
    private $profileTransformer;

    /**
     * @var UserRoleTransformer
     */
    private $userRoleTransformer;

    /**
     * UserTransformer constructor.
     *
     * @param ProfileTransformer  $profileTransformer
     * @param UserRoleTransformer $userRoleTransformer
     * @param NewFileService      $newFileService
     * @param FileTransformer     $fileTransformer
     */
    public function __construct(
        ProfileTransformer $profileTransformer,
        UserRoleTransformer $userRoleTransformer,
        NewFileService $newFileService,
        FileTransformer $fileTransformer
    ) {
        $this->fileTransformer = $fileTransformer;
        $this->newFileService = $newFileService;
        $this->profileTransformer = $profileTransformer;
        $this->userRoleTransformer = $userRoleTransformer;
    }

    /**
     * @param User $user
     *
     * @return string
     */
    public function getType($user): string
    {
        return 'users';
    }

    /**
     * @return array
     */
    protected function getTransformableAttributes(): array
    {
        return ['active', 'avatar', 'email', 'username'];
    }

    /**
     * @param User $user
     *
     * @return array
     */
    public function getRelationships($user): array
    {
        return [
            'profile'   => function (User $user) {
                return ToOneRelationship::create()->setData($user->getProfile(), $this->profileTransformer);
            },
            'userRoles' => function (User $user) {
                return ToManyRelationship::create()->setData($user->getUserRoles(), $this->userRoleTransformer);
            },
        ];
    }

    /**
     * @param User $user
     *
     * @return array|null
     */
    protected function transformAvatar(User $user): ?array
    {
        $model = $this->newFileService->getDataFromField($user, 'avatar');

        if (!$model) {
            return null;
        }

        return $this->fileTransformer->getTransformedAttributes($model);
    }
}
