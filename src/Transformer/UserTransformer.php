<?php

namespace App\Transformer;

use App\Entity\User;
use Aristek\Bundle\JSONAPIBundle\Service\File\FileHandler;
use Aristek\Bundle\JSONAPIBundle\Service\ObjectManagerHelper;
use Aristek\Bundle\JSONAPIBundle\Transformer\AbstractTransformer;
use Doctrine\Common\Persistence\ObjectManager;
use LogicException;
use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use WoohooLabs\Yin\JsonApi\Request\JsonApiRequestInterface;
use WoohooLabs\Yin\JsonApi\Schema\Data\SingleResourceData;
use WoohooLabs\Yin\JsonApi\Schema\Relationship\ToManyRelationship;
use WoohooLabs\Yin\JsonApi\Transformer\Transformation;

/**
 * Class UserTransformer
 */
class UserTransformer extends AbstractTransformer
{
    /**
     * @var ContactTransformer
     */
    private $contactTransformer;

    /**
     * @var DepartmentTransformer
     */
    private $departmentsTransformer;

    /**
     * @var ProfileTransformer
     */
    private $profileTransformer;

    /**
     * @var UserRoleTransformer
     */
    private $userRoleTransformer;

    /**
     * @var ExceptionFactoryInterface
     *
     * @todo: remove this dependency
     */
    private $exceptionFactory;

    /**
     * @var FileTransformer
     *
     * @todo: make abstract
     */
    private $fileTransformer;

    /**
     * @var FileHandler
     *
     * @todo: remove this dependency
     */
    private $fileHandler;

    /**
     * UserTransformer constructor.
     *
     * @param ContactTransformer        $contactTransformer
     * @param DepartmentTransformer     $departmentsTransformer
     * @param ObjectManager             $objectManager
     * @param ObjectManagerHelper       $objectManagerHelper
     * @param ProfileTransformer        $profileTransformer
     * @param UserRoleTransformer       $userRoleTransformer
     * @param FileTransformer           $fileTransformer
     * @param FileHandler               $fileHandler
     * @param ExceptionFactoryInterface $exceptionFactory
     */
    public function __construct(
        ContactTransformer $contactTransformer,
        DepartmentTransformer $departmentsTransformer,
        ObjectManager $objectManager,
        ObjectManagerHelper $objectManagerHelper,
        ProfileTransformer $profileTransformer,
        UserRoleTransformer $userRoleTransformer,
        // @todo: remove next dependencies
        FileTransformer $fileTransformer,
        FileHandler $fileHandler,
        ExceptionFactoryInterface $exceptionFactory
    ) {
        parent::__construct($objectManager, $objectManagerHelper);

        $this->departmentsTransformer = $departmentsTransformer;
        $this->fileTransformer = $fileTransformer;
        $this->fileHandler = $fileHandler;
        $this->profileTransformer = $profileTransformer;
        $this->userRoleTransformer = $userRoleTransformer;
        $this->exceptionFactory = $exceptionFactory;
        $this->contactTransformer = $contactTransformer;
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
    protected function transformProfile(User $user, JsonApiRequestInterface $request): array
    {
        if (!$profile = $user->getProfile()) {
            throw new LogicException(sprintf('User "%s" has no profile.', $user->getId()));
        }

        return $this->profileTransformer->getTransformedAttributes($profile);
        // @todo: use transformToResource
        $data = $this->profileTransformer->transformToResource(
            new Transformation(
                $request,
                new SingleResourceData(),
                $this->exceptionFactory,
                'profile' // required value
            ),
            $profile
        );

        //        dd($data['attributes']);

        return $data;
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
