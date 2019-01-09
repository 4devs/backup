<?php

namespace FDevs\Backup\Tests;

use FDevs\Backup\Exception\DataProviderException;
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
     * @depends testDump
     */
    public function testDumpSourceFail()
    {
        $exception = new DataProviderException();
        $source = $this->getDataProviderMock();
        $source
            ->expects($this->once())
            ->method('dump')
            ->with([])
            ->willThrowException($exception)
        ;

        $filesystem = $this->getFilesystemMock();

        $manager = new Manager($source, $filesystem);

        try {
            $manager->dump();
            $this->fail('Expect DataProviderException::class exception, but that not thrown');
        } catch (\Exception $e) {
            $this->assertSame($exception, $e);
        }
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
        $success = $manager->restore($key);
        $this->assertTrue($success);
    }

    /**
     * @depends testRestore
     */
    public function testRestoreFilesystemFail()
    {
        $key = 'testkey';
        $source = $this->getDataProviderMock();

        $exception = new DataProviderException();
        $filesystem = $this->getFilesystemMock();
        $filesystem
            ->expects($this->once())
            ->method('download')
            ->with($key)
            ->willThrowException($exception)
        ;

        $manager = new Manager($source, $filesystem);

        try {
            $manager->restore($key);
            $this->fail('Expect DataProviderException::class exception, but that not thrown');
        } catch (\Exception $e) {
            $this->assertSame($exception, $e);
        }
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
