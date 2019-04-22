<?php

namespace App\Transformer;

use App\Entity\User;
use Aristek\Bundle\SymfonyJSONAPIBundle\JsonApi\Transformer\AbstractTransformer;
use WoohooLabs\Yin\JsonApi\Schema\Relationship\ToManyRelationship;
use WoohooLabs\Yin\JsonApi\Schema\Relationship\ToOneRelationship;

/**
 * Class UserTransformer
 */
class UserTransformer extends AbstractTransformer
{
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
     */
    public function __construct(ProfileTransformer $profileTransformer, UserRoleTransformer $userRoleTransformer)
    {
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
        return ['active', 'email', 'username'];
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
}
