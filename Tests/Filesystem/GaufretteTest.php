<?php

namespace FDevs\Backup\Tests\Filesystem;

use FDevs\Backup\Filesystem\Gaufrette;
use Gaufrette\Filesystem;

class GaufretteTest extends AbstractFilesystemTest
{

    /**
     * {@inheritdoc}
     */
    protected function createFilesystem()
    {
        $gaufrette = $this->createMock(Filesystem::class);
        $gaufrette
            ->method('keys')
            ->willReturn([])
        ;

        return new Gaufrette($gaufrette, $this->workspace);
    }
}
