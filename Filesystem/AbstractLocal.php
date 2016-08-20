<?php

namespace FDevs\Backup\Filesystem;

use Symfony\Component\Filesystem\Filesystem;

abstract class AbstractLocal implements FilesystemInterface
{
    /**
     * @var string
     */
    protected $dumpDir;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * Local constructor.
     *
     * @param string $dumpDir
     */
    public function __construct($dumpDir)
    {
        $this->dumpDir = rtrim($dumpDir, DIRECTORY_SEPARATOR);
        $this->filesystem = new Filesystem();
    }

    /**
     * {@inheritdoc}
     */
    public function keyList()
    {
        return $this->filesystem->exists($this->dumpDir) ? new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($this->dumpDir, \FilesystemIterator::SKIP_DOTS | \FilesystemIterator::CURRENT_AS_PATHNAME), \RecursiveIteratorIterator::SELF_FIRST) : new \ArrayIterator([]);
    }
}
