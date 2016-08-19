<?php

namespace FDevs\Backup\Tests;

use FDevs\Backup\Compress\TarGzip;
use FDevs\Backup\Filesystem\Local;
use FDevs\Backup\Manager;
use FDevs\Backup\Source\Folder;

class ManagerTest extends AbstractTest
{
    /**
     * @var Manager
     */
    private $manager;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->manager = new Manager(
            new Folder(__DIR__.DIRECTORY_SEPARATOR.'../Source', __DIR__.DIRECTORY_SEPARATOR.'source/Source'),
            new Local(__DIR__.DIRECTORY_SEPARATOR.'source/', __DIR__.DIRECTORY_SEPARATOR.'dump/'),
            new TarGzip(__DIR__.DIRECTORY_SEPARATOR.'dump/')
        );
    }

    public function testDump()
    {
        $this->manager->dump();
    }
}