<?php

namespace App\Transformer;

use App\Entity\User;
use WoohooLabs\Yin\JsonApi\Schema\Links;
use WoohooLabs\Yin\JsonApi\Schema\Resource\AbstractResource;

/**
 * Class UserResourceTransformer
 */
class UserResourceTransformer extends AbstractResource
{
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
     * @return string
     */
    public function getId($user): string
    {
        return (string) $user->getId();
    }

    /**
     * @param User $user
     *
     * @return array
     */
    public function getMeta($user): array
    {
        return [];
    }

    /**
     * @param User $user
     *
     * @return Links|null
     */
    public function getLinks($user): ?Links
    {
        return null;
    }

    /**
     * @param User $user
     *
     * @return array
     */
    public function getAttributes($user): array
    {
        return [
            'active'    => function (User $user) {
                return $user->getActive();
            },
            'email'     => function (User $user) {
                return $user->getEmail();
            },
            'firstName' => function (User $user) {
                return $user->getFirstName();
            },
            'lastName'  => function (User $user) {
                return $user->getLastName();
            },
            'roles'     => function (User $user) {
                return $user->getRoles();
            },
            'username'  => function (User $user) {
                return $user->getUsername();
            },
        ];
    }

    /**
     * @param User $user
     *
     * @return array
     */
    public function getDefaultIncludedRelationships($user): array
    {
        return [];
    }

    /**
     * @param User $user
     *
     * @return array
     */
    public function getRelationships($user): array
    {
        return [];
    }
}
