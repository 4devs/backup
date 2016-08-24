<?php

namespace FDevs\Backup\Filesystem;

interface DownloadInterface
{
    /**
     * @param string $target
     *
     * @return string
     */
    public function download($target);
}
