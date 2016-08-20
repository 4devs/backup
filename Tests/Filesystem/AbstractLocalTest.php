<?php

namespace FDevs\Backup\Tests\Filesystem;

use Symfony\Component\Filesystem\Filesystem;

abstract class AbstractLocalTest extends AbstractFilesystemTest
{
    /**
     * @var string
     */
    protected $workspace;

    /**
     * @var string
     */
    protected $dumpFolder = 'dump';

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->filesystem = new Filesystem();
        $this->workspace = sys_get_temp_dir().'/'.microtime(true).'.'.mt_rand();
        mkdir($this->workspace, 0777, true);
        $this->workspace = realpath($this->workspace);
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        $this->filesystem->remove($this->workspace);
    }
}
