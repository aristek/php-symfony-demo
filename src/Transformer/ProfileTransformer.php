<?php

namespace App\Transformer;

use App\Entity\Profile;
use Aristek\Bundle\JSONAPIBundle\Service\ObjectManagerHelper;
use Aristek\Bundle\JSONAPIBundle\Transformer\AbstractTransformer;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use WoohooLabs\Yin\JsonApi\Schema\Relationship\ToManyRelationship;

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
     * @param EntityManager       $objectManager
     * @param ObjectManagerHelper $objectManagerHelper
     */
    public function __construct(
        ContactTransformer $contactTransformer,
        ObjectManager $objectManager,
        ObjectManagerHelper $objectManagerHelper
    ) {
        parent::__construct($objectManager, $objectManagerHelper);

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
     * @param Profile $profile
     *
     * @return array
     */
    public function getRelationships($profile): array
    {
        return [
            'contacts' => function (Profile $profile) {
                return ToManyRelationship::create()->setData($profile->getContacts(), $this->contactTransformer);
            },
        ];
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
