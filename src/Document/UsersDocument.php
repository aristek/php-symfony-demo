<?php

namespace App\Document;

use Aristek\Bundle\SymfonyJSONAPIBundle\JsonApi\Document\AbstractCollectionDocument;
use WoohooLabs\Yin\JsonApi\Schema\Links;

/**
 * Class UsersDocument
 */
class UsersDocument extends AbstractCollectionDocument
{
    /**
     * @return Links
     */
    public function getLinks(): Links
    {
        return Links::createWithoutBaseUri()->setPagination('/users', $this->domainObject);
    }
}
