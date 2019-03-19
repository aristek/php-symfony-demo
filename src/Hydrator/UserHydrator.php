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
     * @param string                    $clientGeneratedId
     * @param RequestInterface          $request
     * @param ExceptionFactoryInterface $exceptionFactory
     *
     * @return void
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
     * @return string
     */
    protected function generateId(): string
    {
        return '';
    }

    /**
     * @return array
     */
    protected function getAcceptedTypes(): array
    {
        return ['users'];
    }

    /**
     * @param User $user
     *
     * @return array
     */
    protected function getAttributeHydrator($user): array
    {
        return [
            'username'               => [$this, 'hydrateUsername'],
            'email'                  => [$this, 'hydrateEmail'],
            'firstName'              => [$this, 'hydrateFirstName'],
            'lastName'               => [$this, 'hydrateLastName'],
            'password'               => [$this, 'hydratePassword'],
            'passwordChangeRequired' => [$this, 'hydratePasswordChangeRequired'],
            'active'                 => [$this, 'hydrateActive'],
            'passwordChangeToken'    => [$this, 'hydratePasswordChangeToken'],
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

    /**
     * @return array
     */
    protected function getAttributes(): array
    {
        return [
            'username',
            'email',
            'firstName',
            'lastName',
            'password',
            'passwordChangeRequired',
            'active',
            'passwordChangeToken',
        ];
    }

    /**
     * @param User   $user
     * @param string $username
     *
     * @return void
     */
    public function hydrateUsername(User $user, string $username): void
    {
        $user->setUsername($username);
    }

    /**
     * @param User   $user
     * @param string $email
     *
     * @return void
     */
    protected function hydrateEmail(User $user, string $email): void
    {
        $user->setEmail($email);
    }

    /**
     * @param User        $user
     * @param string|null $firstName
     *
     * @return void
     */
    protected function hydrateFirstName(User $user, ?string $firstName): void
    {
        $user->setFirstName($firstName);
    }

    /**
     * @param User        $user
     * @param string|null $lastName
     *
     * @return void
     */
    protected function hydrateLastName(User $user, ?string $lastName): void
    {
        $user->setLastName($lastName);
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
     * @param User $user
     * @param bool $passwordChangeRequired
     *
     * @return void
     */
    protected function hydratePasswordChangeRequired(User $user, bool $passwordChangeRequired): void
    {
        $user->setPasswordChangeRequired($passwordChangeRequired);
    }

    /**
     * @param User $user
     * @param bool $active
     *
     * @return void
     */
    protected function hydrateActive(User $user, bool $active): void
    {
        $user->setActive($active);
    }

    /**
     * @param User        $user
     * @param string|null $passwordChangeToken
     *
     * @return void
     */
    protected function hydratePasswordChangeToken(User $user, ?string $passwordChangeToken): void
    {
        $user->setPasswordChangeToken($passwordChangeToken);
    }
}
