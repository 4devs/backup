<?php

namespace FDevs\Backup\Filesystem;

interface UploadInterface
{
    /**
     * @param string $file
     *
     * @return bool
     */
    public function upload($file);
}
