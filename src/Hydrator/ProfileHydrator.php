<?php

namespace App\Hydrator;

use Aristek\Bundle\JSONAPIBundle\Hydrator\AbstractHydrator;

/**
 * Class ProfileHydrator
 */
class ProfileHydrator extends AbstractHydrator
{
    /**
     * @var string
     */
    protected $acceptedType = 'profiles';

    /**
     * @return array
     */
    protected function getCommonAttributes(): array
    {
        return ['firstName', 'lastName'];
    }
}
