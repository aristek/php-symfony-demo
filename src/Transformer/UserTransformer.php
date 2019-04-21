<?php

namespace App\Transformer;

use App\Entity\User;
use Aristek\Bundle\SymfonyJSONAPIBundle\JsonApi\Transformer\AbstractTransformer;
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
     * UserTransformer constructor.
     *
     * @param ProfileTransformer $profileTransformer
     */
    public function __construct(ProfileTransformer $profileTransformer)
    {
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
        return ['active', 'email', 'roles', 'username'];
    }

    /**
     * @param User $user
     *
     * @return array
     */
    public function getRelationships($user): array
    {
        return [
            'profileAttributes' => function (User $user) {
                return ToOneRelationship::create()->setData($user->getProfile(), $this->profileTransformer);
            },
        ];
    }
}
