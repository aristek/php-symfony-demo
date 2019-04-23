<?php

namespace App\Transformer;

use App\Entity\User;
use Aristek\Bundle\SymfonyJSONAPIBundle\JsonApi\Transformer\AbstractTransformer;
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
     * UserTransformer constructor.
     *
     * @param ProfileTransformer $profileTransformer
     * @param NewFileService     $newFileService
     * @param FileTransformer    $fileTransformer
     */
    public function __construct(
        ProfileTransformer $profileTransformer,
        NewFileService $newFileService,
        FileTransformer $fileTransformer
    ) {
        $this->fileTransformer = $fileTransformer;
        $this->newFileService = $newFileService;
        $this->profileTransformer = $profileTransformer;
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
        return ['active', 'avatar', 'email', 'roles', 'username'];
    }

    /**
     * @param User $user
     *
     * @return array
     */
    public function getRelationships($user): array
    {
        return [
            'profile' => function (User $user) {
                return ToOneRelationship::create()->setData($user->getProfile(), $this->profileTransformer);
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
