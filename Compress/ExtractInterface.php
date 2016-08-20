<?php

namespace FDevs\Backup\Compress;

use FDevs\Backup\Exception\ExtractException;

interface ExtractInterface
{
    /**
     * @param string $archive
     * @param string $source
     *
     * @return string
     *
     * @throws ExtractException
     */
    public function unpack($archive, $source);
}
