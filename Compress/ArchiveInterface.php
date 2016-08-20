<?php

namespace FDevs\Backup\Compress;

use FDevs\Backup\Exception\ArchiveException;

interface ArchiveInterface
{
    /**
     * @param string $source
     * @param string $archive
     *
     * @return bool
     *
     * @throws ArchiveException
     */
    public function pack($source, $archive);
}
