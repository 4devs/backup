<?php

namespace FDevs\Backup\Compress;

interface CompressionInterface extends ArchiveInterface, ExtractInterface
{
    /**
     * @return string
     */
    public function getExtension();
}
