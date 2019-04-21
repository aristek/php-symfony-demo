<?php

namespace App\Transformer;

use App\Entity\Profile;
use Aristek\Bundle\SymfonyJSONAPIBundle\JsonApi\Transformer\AbstractTransformer;

/**
 * Class ProfileTransformer
 */
class ProfileTransformer extends AbstractTransformer
{
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
     * @return string
     */
    public function getType($profile): string
    {
        return 'profiles';
    }
}
