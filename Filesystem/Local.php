<?php

namespace FDevs\Backup\Filesystem;

use Symfony\Component\Filesystem\Filesystem;

class Local implements FilesystemInterface
{
    /**
     * @var string
     */
    private $baseDir;
    /**
     * @var string
     */
    private $dumpDir;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * Local constructor.
     *
     * @param string $baseDir
     * @param string $dumpDir
     */
    public function __construct($baseDir, $dumpDir)
    {
        $this->baseDir = $baseDir;
        $this->dumpDir = $dumpDir;
        $this->filesystem = new Filesystem();
    }

    /**
     * {@inheritdoc}
     */
    public function download($target)
    {
        $key = $this->baseDir.DIRECTORY_SEPARATOR.uniqid(mt_rand());
        $this->filesystem->copy($target, $key);

        return $key;
    }

    /**
     * {@inheritdoc}
     */
    public function keyList()
    {
        return glob($this->dumpDir);
    }

    /**
     * {@inheritdoc}
     */
    public function upload($file)
    {
        $this->filesystem->copy($file, $this->dumpDir.DIRECTORY_SEPARATOR.basename($file));

        return true;
    }
}
