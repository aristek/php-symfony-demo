<?php

namespace App\Transformer;

use App\Entity\Profile;
use Aristek\Bundle\SymfonyJSONAPIBundle\Transformer\AbstractTransformer;

/**
 * Class DepartmentTransformer
 */
class DepartmentTransformer extends AbstractTransformer
{
    /**
     * @return array
     */
    protected function getTransformableAttributes(): array
    {
        return ['name'];
    }

    /**
     * @param Profile $profile
     *
     * @return string
     */
    public function getType($profile): string
    {
        return 'departments';
    }
}
