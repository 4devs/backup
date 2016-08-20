<?php

namespace FDevs\Backup\Filesystem;

class LocalFolder extends AbstractLocal
{
    /**
     * {@inheritdoc}
     */
    public function download($target)
    {
        $key = $this->dumpDir.DIRECTORY_SEPARATOR.uniqid(mt_rand());
        $this->filesystem->mirror($target, $key);

        return $key;
    }

    /**
     * {@inheritdoc}
     */
    public function upload($file)
    {
        $this->filesystem->mirror($file, $this->dumpDir.DIRECTORY_SEPARATOR.basename($file));

        return true;
    }
}
