<?php

namespace App\Factory;

use WoohooLabs\Yin\JsonApi\Schema\Error\Error;

/**
 * Interface ErrorFactoryInterface
 */
interface ErrorFactoryInterface
{
    /**
     * @param string $message
     * @param array  $meta
     *
     * @return Error
     */
    public function create(string $message, array $meta = []): Error;
}
