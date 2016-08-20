<?php

namespace FDevs\Backup\Tests;

use FDevs\Backup\Filesystem\FilesystemInterface;
use FDevs\Backup\Manager;
use FDevs\Backup\Source\DataProviderInterface;

class ManagerTest extends AbstractTest
{
    /**
     * test list.
     */
    public function testListKey()
    {
        $source = $this->getDataProviderMock();
        $filesystem = $this->getFilesystemMock();
        $filesystem
            ->expects($this->once())
            ->method('keyList')
            ->willReturn(new \ArrayIterator())
        ;

        $manager = new Manager($source, $filesystem);
        $list = $manager->keyList();
        $this->assertInstanceOf(\Iterator::class, $list);
        $this->assertEquals(null, $list->current());
    }

    /**
     * test dump.
     */
    public function testDump()
    {
        $source = $this->getDataProviderMock();
        $source
            ->expects($this->once())
            ->method('dump')
            ->with([])
            ->willReturn('testdump')
        ;

        $filesystem = $this->getFilesystemMock();
        $filesystem
            ->expects($this->once())
            ->method('upload')
            ->with('testdump')
            ->willReturn('uploadtestdump')
        ;

        $manager = new Manager($source, $filesystem);
        $key = $manager->dump();
        $this->assertEquals('uploadtestdump', $key);
    }

    /**
     * @depends  testDump
     * @depends  testListKey
     */
    public function testRestore()
    {
        $key = 'testkey';
        $source = $this->getDataProviderMock();
        $source
            ->expects($this->once())
            ->method('restore')
            ->with('downloadtest')
            ->willReturn(true)
        ;

        $filesystem = $this->getFilesystemMock();
        $filesystem
            ->expects($this->once())
            ->method('download')
            ->with($key)
            ->willReturn('downloadtest')
        ;

        $manager = new Manager($source, $filesystem);
        $key = $manager->restore($key);
        $this->assertTrue($key);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|DataProviderInterface
     */
    protected function getDataProviderMock()
    {
        return $this->createMock(DataProviderInterface::class);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|FilesystemInterface
     */
    protected function getFilesystemMock()
    {
        return $this->createMock(FilesystemInterface::class);
    }
}
