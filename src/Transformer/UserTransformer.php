<?php

namespace App\Transformer;

use App\Entity\User;
use Aristek\Bundle\SymfonyJSONAPIBundle\JsonApi\Transformer\AbstractTransformer;
use Aristek\Bundle\SymfonyJSONAPIBundle\Service\File\NewFileService;
use WoohooLabs\Yin\JsonApi\Schema\Relationship\ToManyRelationship;
use WoohooLabs\Yin\JsonApi\Schema\Relationship\ToOneRelationship;

/**
 * Class UserTransformer
 */
class UserTransformer extends AbstractTransformer
{
    /**
     * @var DepartmentTransformer
     */
    private $departmentsTransformer;

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
     * @param DepartmentTransformer $departmentsTransformer
     * @param FileTransformer       $fileTransformer
     * @param NewFileService        $newFileService
     * @param ProfileTransformer    $profileTransformer
     * @param UserRoleTransformer   $userRoleTransformer
     */
    public function __construct(
        DepartmentTransformer $departmentsTransformer,
        FileTransformer $fileTransformer,
        NewFileService $newFileService,
        ProfileTransformer $profileTransformer,
        UserRoleTransformer $userRoleTransformer
    ) {
        $this->departmentsTransformer = $departmentsTransformer;
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
        return ['active', 'avatar', 'email', 'username', 'profile'];
    }

    /**
     * @param User $user
     *
     * @return array
     */
    public function getRelationships($user): array
    {
        return [
            // @todo: replace with Department example
            'profile'     => function (User $user) {
                return ToOneRelationship::create()->setData($user->getProfile(), $this->profileTransformer);
            },
            'departments' => function (User $user) {
                return ToManyRelationship::create()->setData($user->getDepartments(), $this->departmentsTransformer);
            },
            'userRoles'   => function (User $user) {
                return ToManyRelationship::create()->setData($user->getUserRoles(), $this->userRoleTransformer);
            },
        ];
    }

    /**
     * @param User $user
     *
     * @return array
     */
    protected function transformProfile(User $user): array
    {
        if (!$profile = $user->getProfile()) {
            throw new \LogicException(sprintf('User "%s" has no profile.', $user->getId()));
        }

        return [
            'firstName' => $profile->getFirstName(),
            'lastName'  => $profile->getLastName(),
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
