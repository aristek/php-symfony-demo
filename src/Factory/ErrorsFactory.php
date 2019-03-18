<?php

namespace App\Factory;

/**
 * Class ErrorsFactory
 */
class ErrorsFactory
{
    /**
     * @param ErrorFactoryInterface $factory
     * @param array                 $errorMessages
     * @param array                 $meta
     *
     * @return array
     */
    public function create(ErrorFactoryInterface $factory, array $errorMessages, array $meta = []): array
    {
        $errors = [];

        foreach ($errorMessages as $message) {
            $errors[] = $factory->create($message, $meta);
        }

        return $errors;
    }
}
