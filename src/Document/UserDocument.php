<?php

namespace App\Document;

use Aristek\Bundle\SymfonyJSONAPIBundle\JsonApi\Document\AbstractSingleResourceDocument;
use WoohooLabs\Yin\JsonApi\Schema\Link\Link;
use WoohooLabs\Yin\JsonApi\Schema\Links;

/**
 * Class UserDocument
 */
class UserDocument extends AbstractSingleResourceDocument
{
    /**
     * @return Links
     */
    public function getLinks(): Links
    {
        return Links::createWithoutBaseUri(
            ['self' => new Link($this->router->generate('users_show', ['id' => $this->getResourceId()]))]
        );
    }
}
