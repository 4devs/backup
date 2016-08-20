<?php

namespace FDevs\Backup\Filesystem;

interface UploadInterface
{
    /**
     * @param string $file
     *
     * @return string
     */
    public function upload($file);
}
