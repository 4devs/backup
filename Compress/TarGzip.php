<?php

namespace FDevs\Backup\Compress;

class TarGzip extends AbstractCompression
{
    /**
     * {@inheritdoc}
     */
    protected function getPackCommand($source, $target)
    {
        return sprintf('tar -cvzf %s -C %s .', $target, $source);
    }

    /**
     * {@inheritdoc}
     */
    protected function getUnpackCommand($source, $target)
    {
        return sprintf('tar -xvzf %s -C %s', $source, $target);
    }

    /**
     * {@inheritdoc}
     */
    public function getExtension()
    {
        return '.tar.gz';
    }
}
