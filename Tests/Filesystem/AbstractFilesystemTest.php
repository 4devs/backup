<?php

namespace FDevs\Backup\Tests\Filesystem;

use FDevs\Backup\Filesystem\FilesystemInterface;
use FDevs\Backup\Tests\AbstractTest;

abstract class AbstractFilesystemTest extends AbstractTest
{
    /**
     * @return FilesystemInterface
     */
    abstract protected function createFilesystem();

    /**
     * test key list.
     */
    public function testKeyListInstance()
    {
        $fs = $this->createFilesystem();
        $this->assertInstanceOf(\Iterator::class, $fs->keyList());
    }
}
