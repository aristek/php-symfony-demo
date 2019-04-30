<?php

namespace App\Transformer;

use App\Entity\User;
use Aristek\Bundle\SymfonyJSONAPIBundle\JsonApi\Transformer\AbstractTransformer;
use Aristek\Bundle\SymfonyJSONAPIBundle\Service\File\FileHandler;
use LogicException;
use WoohooLabs\Yin\JsonApi\Schema\Relationship\ToManyRelationship;

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
     * @var FileHandler
     */
    private $fileHandler;

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
     * @param FileHandler           $fileHandler
     * @param ProfileTransformer    $profileTransformer
     * @param UserRoleTransformer   $userRoleTransformer
     */
    public function __construct(
        DepartmentTransformer $departmentsTransformer,
        FileTransformer $fileTransformer,
        FileHandler $fileHandler,
        ProfileTransformer $profileTransformer,
        UserRoleTransformer $userRoleTransformer
    ) {
        $this->departmentsTransformer = $departmentsTransformer;
        $this->fileTransformer = $fileTransformer;
        $this->fileHandler = $fileHandler;
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
     * @param User $user
     *
     * @return array
     */
    public function getRelationships($user): array
    {
        return [
            'departments' => function (User $user) {
                return ToManyRelationship::create()->setData($user->getDepartments(), $this->departmentsTransformer);
            },
            'userRoles'   => function (User $user) {
                return ToManyRelationship::create()->setData($user->getUserRoles(), $this->userRoleTransformer);
            },
        ];
    }

    /**
     * @return AbstractTransformer[]
     */
    protected function getRelationshipTransformers(): array
    {
        return [
            'departments' => $this->departmentsTransformer,
            'userRoles'   => $this->userRoleTransformer,
        ];
    }

    /**
     * @return AbstractTransformer[]
     */
    protected function getChildrenTransformers(): array
    {
        return [
            'profile' => $this->profileTransformer,
        ];
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
     *
     * @throws LogicException
     */
    protected function transformProfile(User $user): array
    {
        if (!$profile = $user->getProfile()) {
            throw new LogicException(sprintf('User "%s" has no profile.', $user->getId()));
        }

        // @todo: use transformToResource
        return $this->profileTransformer->getTransformedAttributes($profile);
    }

    /**
     * @param User $user
     *
     * @return array|null
     */
    protected function transformAvatar(User $user): ?array
    {
        if (!$model = $this->fileHandler->getValue($user, 'avatar')) {
            return null;
        }

        return $this->fileTransformer->getTransformedAttributes($model);
    }
}
