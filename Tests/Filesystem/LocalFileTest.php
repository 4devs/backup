<?php

namespace FDevs\Backup\Tests\Filesystem;

use FDevs\Backup\Filesystem\LocalFile;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;

class LocalFileTest extends AbstractLocalTest
{
    /**
     * test expect exception.
     */
    public function testUploadException()
    {
        $this->expectException(FileNotFoundException::class);
        $localFs = $this->createFilesystem();
        $localFs->upload(uniqid());
    }

    /**
     * test expect exception.
     */
    public function testDownloadException()
    {
        $this->expectException(FileNotFoundException::class);
        $localFs = $this->createFilesystem();
        $localFs->download(uniqid());
    }

    /**
     * test upload file.
     */
    public function testUpload()
    {
        $localFs = $this->createFilesystem();
        $upload = $localFs->upload($this->source);
        $this->assertEquals($this->workspace.DIRECTORY_SEPARATOR.$this->dumpFolder.DIRECTORY_SEPARATOR.basename($this->source), $upload);
        $this->assertFileExists($upload);
        $this->assertFileEquals($upload, $this->source);
    }

    /**
     * test download.
     *
     * @depends testUpload
     */
    public function testDownload()
    {
        $localFs = $this->createFilesystem();
        $upload = $localFs->upload($this->source);
        $file = $localFs->download($upload);
        $this->assertStringStartsWith($this->workspace.DIRECTORY_SEPARATOR.$this->dumpFolder, $file);
        $this->assertFileExists($file);
        $this->assertFileEquals($file, $this->source);
    }

    /**
     * {@inheritdoc}
     */
    protected function createFilesystem()
    {
        return new LocalFile($this->workspace.DIRECTORY_SEPARATOR.$this->dumpFolder);
    }
}
