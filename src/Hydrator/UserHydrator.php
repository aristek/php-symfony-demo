<?php

namespace App\Hydrator;

use App\Entity\User;
use Aristek\Bundle\SymfonyJSONAPIBundle\JsonApi\Hydrator\AbstractHydrator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use WoohooLabs\Yin\JsonApi\Request\RequestInterface;

/**
 * Class UserHydrator
 */
class UserHydrator extends AbstractHydrator
{
    /**
     * {@inheritdoc}
     */
    protected function validateClientGeneratedId(
        string $clientGeneratedId,
        RequestInterface $request,
        ExceptionFactoryInterface $exceptionFactory
    ): void {
        if (!empty($clientGeneratedId)) {
            throw $exceptionFactory->createClientGeneratedIdNotSupportedException(
                $request,
                $clientGeneratedId
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function generateId(): string
    {
        return '';
    }

    /**
     * {@inheritdoc}
     */
    protected function getAcceptedTypes(): array
    {
        return ['users'];
    }

    /**
     * {@inheritdoc}
     */
    protected function getAttributeHydrator($user): array
    {
        return [
            'username'               => function (User $user, string $username) {
                $user->setUsername($username);
            },
            'email'                  => function (User $user, string $email) {
                $user->setEmail($email);
            },
            'firstName'              => function (User $user, ?string $firstName) {
                $user->setFirstName($firstName);
            },
            'lastName'               => function (User $user, ?string $lastName) {
                $user->setLastName($lastName);
            },
            'password'               => function (User $user, ?string $plainPassword) {
                if ($plainPassword) {
                    $user->setPlainPassword($plainPassword);
                }
            },
            'passwordChangeRequired' => function (User $user, bool $passwordChangeRequired) {
                $user->setPasswordChangeRequired($passwordChangeRequired);
            },
            'active'                 => function (User $user, bool $active) {
                $user->setActive($active);
            },
            'passwordChangeToken'    => function (User $user, ?string $passwordChangeToken) {
                $user->setPasswordChangeToken($passwordChangeToken);
            },
        ];
    }

    /**
     * @return array
     */
    protected function getAdditionalFields(): array
    {
        return ['projectAccessesIds'];
    }

    /**
     * @param RequestInterface $request
     */
    protected function validateRequest(RequestInterface $request): void
    {
        $attributes = array_merge($this->getAdditionalFields(), array_keys($this->getAttributeHydrator(null)));
        $attributes = array_unique($attributes);
        $this->validateFields($attributes, $request->getResourceAttributes());
    }

    /**
     * @param User   $user
     * @param string $id
     */
    protected function setId($user, string $id): void
    {
        if ($id && (int) $user->getId() !== (int) $id) {
            throw new NotFoundHttpException('both ids in url & body bust be same');
        }
    }

    /**
     * @param User $user
     *
     * @return array
     */
    protected function getRelationshipHydrator($user): array
    {
        return [];
    }
}
