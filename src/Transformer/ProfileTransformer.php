<?php

namespace App\Transformer;

use App\Entity\Profile;
use Aristek\Bundle\JSONAPIBundle\Service\File\FileHandler;
use Aristek\Bundle\JSONAPIBundle\Service\ObjectManagerHelper;
use Aristek\Bundle\JSONAPIBundle\Transformer\AbstractTransformer;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class ProfileTransformer
 */
class ProfileTransformer extends AbstractTransformer
{
    /**
     * @var ContactTransformer
     */
    private $contactTransformer;

    /**
     * ProfileTransformer constructor.
     *
     * @param ContactTransformer  $contactTransformer
     * @param FileHandler         $fileHandler
     * @param ObjectManager       $objectManager
     * @param ObjectManagerHelper $objectManagerHelper
     */
    public function __construct(
        ContactTransformer $contactTransformer,
        FileHandler $fileHandler,
        ObjectManager $objectManager,
        ObjectManagerHelper $objectManagerHelper
    ) {
        parent::__construct($fileHandler, $objectManager, $objectManagerHelper);

        $this->contactTransformer = $contactTransformer;
    }

    /**
     * @return array
     */
    protected function getTransformableAttributes(): array
    {
        return ['firstName', 'lastName'];
    }

    /**
     * @return AbstractTransformer[]
     */
    protected function getRelationshipTransformers(): array
    {
        return ['contacts' => $this->contactTransformer];
    }

    /**
     * @param Profile $profile
     *
     * @return string
     */
    public function getType($profile): string
    {
        return 'profiles';
    }
}
