<?php

namespace App\Hydrator;

use App\Entity\User;
use Aristek\Bundle\SymfonyJSONAPIBundle\JsonApi\Hydrator\AbstractHydrator;
use Aristek\Bundle\SymfonyJSONAPIBundle\Service\WrongFieldsLogger;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use WoohooLabs\Yin\JsonApi\Request\RequestInterface;

/**
 * Abstract User Hydrator.
 */
class UserHydrator extends AbstractHydrator
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * UserHydrator constructor.
     *
     * @param ObjectManager                $objectManager
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param                              $wrongFieldsLogger
     */
    public function __construct(
        ObjectManager $objectManager,
        UserPasswordEncoderInterface $passwordEncoder,
        WrongFieldsLogger $wrongFieldsLogger
    ) {
        parent::__construct($objectManager, $wrongFieldsLogger);

        $this->passwordEncoder = $passwordEncoder;
    }

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
        /**
         * All possible callback params
         * @see \WoohooLabs\Yin\JsonApi\Hydrator\HydratorTrait::hydrateAttributes()
         */
        return [
            'username'               => function (User $user, string $attribute) {
                $user->setUsername($attribute);
            },
            'email'                  => function (User $user, string $attribute) {
                $user->setEmail($attribute);
            },
            'firstName'              => function (User $user, ?string $attribute) {
                $user->setFirstName($attribute);
            },
            'lastName'               => function (User $user, ?string $attribute) {
                $user->setLastName($attribute);
            },
            'password'               => function (User $user, ?string $attribute) {
                if ($attribute) {
                    $user->setPlainPassword($attribute);
                    $user->setPassword($this->passwordEncoder->encodePassword($user, $attribute));
                }
            },
            'passwordChangeRequired' => function (User $user, bool $attribute) {
                $user->setPasswordChangeRequired($attribute);
            },
            'active'                 => function (User $user, bool $attribute) {
                $user->setActive($attribute);
            },
            'passwordChangeToken'    => function (User $user, ?string $attribute) {
                $user->setPasswordChangeToken($attribute);
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
     * {@inheritdoc}
     */
    protected function getRelationshipHydrator($user): array
    {
        return [];
    }
}
