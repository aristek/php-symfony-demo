<?php

namespace App\Hydrator;

use Aristek\Bundle\JSONAPIBundle\Hydrator\AbstractHydrator;

/**
 * Class UserRoleHydrator
 */
class UserRoleHydrator extends AbstractHydrator
{
    /**
     * @var string
     */
    protected $acceptedType = 'userRoles';

    /**
     * @return array
     */
    protected function getCommonAttributes(): array
    {
        return ['active', 'role'];
    }
}
