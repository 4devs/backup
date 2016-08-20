<?php

namespace FDevs\Backup\Filesystem;

use Symfony\Component\Filesystem\Filesystem;

class LocalFile extends AbstractLocal
{
    /**
     * {@inheritdoc}
     */
    public function download($target)
    {
        $key = $this->dumpDir.DIRECTORY_SEPARATOR.uniqid(mt_rand());
        $this->filesystem->copy($target, $key);

        return $key;
    }

    /**
     * {@inheritdoc}
     */
    public function upload($file)
    {
        $key = $this->dumpDir.DIRECTORY_SEPARATOR.basename($file);
        $this->filesystem->copy($file, $key);

        return $key;
    }
}
