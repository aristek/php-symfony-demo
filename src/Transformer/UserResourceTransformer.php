<?php

namespace App\Transformer;

use App\Entity\User;
use WoohooLabs\Yin\JsonApi\Schema\Links;
use WoohooLabs\Yin\JsonApi\Schema\Resource\AbstractResource;

/**
 * User Resource Transformer.
 */
class UserResourceTransformer extends AbstractResource
{
    /**
     * {@inheritdoc}
     */
    public function getType($user): string
    {
        return 'users';
    }

    /**
     * @param User $user
     *
     * @return string
     */
    public function getId($user): string
    {
        return (string) $user->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function getMeta($user): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getLinks($user): ?Links
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributes($user): array
    {
        return [
            'username'         => function (User $user) {
                return $user->getUsername();
            },
            'firstName'        => function (User $user) {
                return $user->getFirstName();
            },
            'lastName'         => function (User $user) {
                return $user->getLastName();
            },
            'projectRoles'     => function (User $user) {
                return $user->getProjectRoles();
            },
            'email'            => function (User $user) {
                return $user->getEmail();
            },
            'active'           => function (User $user) {
                return $user->getActive();
            },
            'productionAccess' => function (User $user) {
                return $user->getProductionAccess();
            },
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultIncludedRelationships($user): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getRelationships($user): array
    {
        return [];
    }
}
