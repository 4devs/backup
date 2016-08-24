<?php

namespace FDevs\Backup\Filesystem;

use Gaufrette\Filesystem;
use Symfony\Component\Filesystem\Filesystem as LocalFilesystem;

class Gaufrette implements FilesystemInterface
{
    /**
     * @var Filesystem
     */
    private $gaufrette;

    /**
     * @var string
     */
    private $cacheDir;

    /**
     * @var LocalFilesystem
     */
    private $filesystem;

    /**
     * Gaufrette constructor.
     *
     * @param Filesystem $gaufrette
     * @param string     $cacheDir
     */
    public function __construct(Filesystem $gaufrette, $cacheDir = __DIR__)
    {
        $this->gaufrette = $gaufrette;
        $this->filesystem = new LocalFilesystem();
        $this->cacheDir = $cacheDir;
    }

    /**
     * {@inheritdoc}
     */
    public function download($target)
    {
        $key = $this->cacheDir.DIRECTORY_SEPARATOR.uniqid(mt_rand());
            $this->filesystem->dumpFile($key, $this->gaufrette->read($target));

        return $key;
    }

    /**
     * {@inheritdoc}
     */
    public function keyList()
    {
        return new \ArrayIterator($this->gaufrette->keys());
    }

    /**
     * {@inheritdoc}
     */
    public function upload($file)
    {
        $key = basename($file);
        $this->gaufrette->write($key, file_get_contents($file));

        return $key;
    }
}
