<?php

namespace App\Transformer;

use Aristek\Bundle\SymfonyJSONAPIBundle\JsonApi\Transformer\AbstractTransformer;
use Aristek\Bundle\SymfonyJSONAPIBundle\Service\File\Model\UploadedFileModel;

/**
 * Class FileTransformer
 */
class FileTransformer extends AbstractTransformer
{
    /**
     * @param UploadedFileModel $uploadedFileModel
     *
     * @return string
     */
    public function getType($uploadedFileModel): string
    {
        return 'files';
    }

    /**
     * @return array
     */
    protected function getTransformableAttributes(): array
    {
        return ['id', 'name', 'url', 'size', 'mimeType'];
    }
}
