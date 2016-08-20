<?php

namespace FDevs\Backup\Tests\Filesystem;

use FDevs\Backup\Filesystem\LocalFile;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;

class LocalFileTest extends AbstractLocalTest
{
    /**
     * @var string
     */
    private $source;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();
        $this->source = $this->workspace.DIRECTORY_SEPARATOR.'tmpfile.sql';
        $fp = fopen($this->source, 'w+');
        fwrite($fp, 'rand'.mt_rand());
    }

    /**
     * test expect exception
     */
    public function testUploadException()
    {
        $this->expectException(FileNotFoundException::class);
        $localFs = $this->createFilesystem();
        $localFs->upload(uniqid());
    }

    /**
     * test expect exception
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
        $this->assertInternalType('string', $upload);
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
        $this->assertInternalType('string', $file);
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
