<?php

namespace FDevs\Backup\Tests\Compression;

use FDevs\Backup\Compress\TarGzip;

class TarGzipTest extends AbstractCompressionTest
{
    /**
     * {@inheritdoc}
     */
    protected function getInstance()
    {
        return new TarGzip();
    }

    /**
     * {@inheritdoc}
     */
    protected function exceptedExtension()
    {
        return '.tar.gz';
    }
}


