<?php

namespace App\Transformer;

use App\Entity\Contact;
use Aristek\Bundle\SymfonyJSONAPIBundle\Transformer\AbstractTransformer;

/**
 * Class ContactTransformer
 */
class ContactTransformer extends AbstractTransformer
{
    /**
     * @return array
     */
    protected function getTransformableAttributes(): array
    {
        return ['phone', 'email'];
    }

    /**
     * @param Contact $domainObject
     *
     * @return string
     */
    public function getType($domainObject): string
    {
        return 'contacts';
    }
}
