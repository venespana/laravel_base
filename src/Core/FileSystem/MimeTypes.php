<?php

namespace VD\Core\FileSystem;

use Mimey\MimeTypes as MMimetypes;

class MimeTypes extends MMimeTypes
{
    public function getGroup(string $value)
    {
        $result = $this->getExtension($value);
        $mime = $this->getMimeType($result ?? $value);

        $result = explode('/', $mime);
        return $result[0];
    }
}