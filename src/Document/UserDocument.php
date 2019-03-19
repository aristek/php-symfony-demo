<?php

namespace App\Document;

use WoohooLabs\Yin\JsonApi\Schema\Document\AbstractSingleResourceDocument;
use WoohooLabs\Yin\JsonApi\Schema\JsonApiObject;
use WoohooLabs\Yin\JsonApi\Schema\Link\Link;
use WoohooLabs\Yin\JsonApi\Schema\Links;

/**
 * Class UserDocument
 */
class UserDocument extends AbstractSingleResourceDocument
{
    /**
     * @return JsonApiObject
     */
    public function getJsonApi(): JsonApiObject
    {
        return new JsonApiObject('1.0');
    }

    /**
     * @return array
     */
    public function getMeta(): array
    {
        return [];
    }

    /**
     * @return Links
     */
    public function getLinks(): Links
    {
        return Links::createWithoutBaseUri(['self' => new Link('/users/'.$this->getResourceId())]);
    }
}
