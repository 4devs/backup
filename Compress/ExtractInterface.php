<?php

namespace FDevs\Backup\Compress;

use FDevs\Backup\Exception\ExtractException;

interface ExtractInterface
{
    /**
     * @param string $archive
     *
     * @return string
     *
     * @throws ExtractException
     */
    public function unpack($archive);
}
