<?php

namespace App\Transformer;

use App\Entity\User;
use Aristek\Bundle\JSONAPIBundle\Service\File\FileHandler;
use Aristek\Bundle\JSONAPIBundle\Service\ObjectManagerHelper;
use Aristek\Bundle\JSONAPIBundle\Transformer\AbstractTransformer;
use Doctrine\Common\Persistence\ObjectManager;

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
     * @param ObjectManager         $objectManager
     * @param ObjectManagerHelper   $objectManagerHelper
     * @param ProfileTransformer    $profileTransformer
     * @param UserRoleTransformer   $userRoleTransformer
     * @param FileHandler           $fileHandler
     */
    public function __construct(
        DepartmentTransformer $departmentsTransformer,
        FileHandler $fileHandler,
        ObjectManager $objectManager,
        ObjectManagerHelper $objectManagerHelper,
        ProfileTransformer $profileTransformer,
        UserRoleTransformer $userRoleTransformer
    ) {
        parent::__construct($fileHandler, $objectManager, $objectManagerHelper);

        $this->departmentsTransformer = $departmentsTransformer;
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
        return ['profile' => $this->profileTransformer];
    }

    /**
     * @return array
     */
    protected function getTransformableAttributes(): array
    {
        return ['active', 'avatar', 'email', 'username', 'profile'];
    }
}
