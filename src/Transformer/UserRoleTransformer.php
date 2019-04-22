<?php

namespace App\Transformer;

use App\Entity\Profile;
use Aristek\Bundle\SymfonyJSONAPIBundle\JsonApi\Transformer\AbstractTransformer;

/**
 * Class UserRoleTransformer
 */
class UserRoleTransformer extends AbstractTransformer
{
    /**
     * @return array
     */
    protected function getTransformableAttributes(): array
    {
        return ['active'];
    }

    /**
     * @param Profile $profile
     *
     * @return string
     */
    public function getType($profile): string
    {
        return 'userRoles';
    }
}
