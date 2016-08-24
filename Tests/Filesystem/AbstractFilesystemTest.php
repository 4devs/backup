<?php

namespace FDevs\Backup\Tests\Filesystem;

use FDevs\Backup\Filesystem\FilesystemInterface;
use FDevs\Backup\Tests\AbstractTest;
use Symfony\Component\Filesystem\Filesystem;

abstract class AbstractFilesystemTest extends AbstractTest
{
    /**
     * @return FilesystemInterface
     */
    abstract protected function createFilesystem();

    /**
     * @var string
     */
    protected $source;
    /**
     * @var string
     */
    protected $workspace;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();
        $this->filesystem = new Filesystem();
        $this->workspace = sys_get_temp_dir().'/'.microtime(true).'.'.mt_rand();
        mkdir($this->workspace, 0777, true);
        $this->source = $this->workspace.DIRECTORY_SEPARATOR.'tmpfile.sql';
        $fp = fopen($this->source, 'w+');
        fwrite($fp, 'rand'.mt_rand());
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        parent::tearDown();
        $this->filesystem->remove($this->workspace);
    }

    /**
     * test key list.
     */
    public function testKeyListInstance()
    {
        $fs = $this->createFilesystem();
        $this->assertInstanceOf(\Iterator::class, $fs->keyList());
    }

    /**
     * tes key upload.
     */
    public function testKeyUpload()
    {
        $fs = $this->createFilesystem();
        $file = $fs->upload($this->source);
        $this->assertInternalType('string', $file);
    }

    /**
     * test download.
     *
     * @depends testKeyUpload
     */
    public function testKeyDownload()
    {
        $fs = $this->createFilesystem();
        $file = $fs->download($fs->upload($this->source));
        $this->assertInternalType('string', $file);
    }
}
