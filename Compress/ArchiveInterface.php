<?php

namespace FDevs\Backup\Compress;

use FDevs\Backup\Exception\ArchiveException;

interface ArchiveInterface
{
    /**
     * @param string $source
     *
     * @return string
     *
     * @throws ArchiveException
     */
    public function pack($source);
}
