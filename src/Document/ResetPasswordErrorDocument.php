<?php

namespace App\Document;

use Aristek\Bundle\SymfonyJSONAPIBundle\JsonApi\Document\AbstractErrorDocument;
use WoohooLabs\Yin\JsonApi\Schema\Link\Link;
use WoohooLabs\Yin\JsonApi\Schema\Links;

/**
 * Class ResetPasswordErrorDocument
 */
class ResetPasswordErrorDocument extends AbstractErrorDocument
{
    /**
     * @var int
     */
    private $resourceId;

    /**
     * ResetPasswordErrorDocument constructor.
     *
     * @param int $resourceId
     */
    public function __construct(int $resourceId)
    {
        $this->resourceId = $resourceId;
    }

    /**
     * @return Links|null
     */
    public function getLinks(): ?Links
    {
        return Links::createWithoutBaseUri(
            [
                'self' => new Link(sprintf('/users/%d/reset-password', $this->resourceId)),
            ]
        );
    }
}
