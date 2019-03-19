<?php

namespace App\Factory;

use WoohooLabs\Yin\JsonApi\Schema\Error\Error;

/**
 * Class ResetPasswordErrorFactory
 */
class ResetPasswordErrorFactory implements ErrorFactoryInterface
{
    private const ERROR_STATUS = '500';
    private const ERROR_CODE = 'RESET_PASSWORD_ERROR';

    /**
     * @param string $message
     * @param array  $meta
     *
     * @return Error
     */
    public function create(string $message, array $meta = []): Error
    {
        $error = new Error();

        $error
            ->setTitle($message)
            ->setCode(static::ERROR_CODE)
            ->setStatus(static::ERROR_STATUS)
            ->setMeta($meta);

        return $error;
    }
}
