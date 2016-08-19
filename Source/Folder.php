<?php

namespace FDevs\Backup\Source;

use Symfony\Component\Filesystem\Filesystem;

class Folder implements SourceInterface
{
    /**
     * @var string
     */
    private $sourceFolder;

    /**
     * @var string
     */
    private $distFolder;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * Folder constructor.
     *
     * @param string $sourceFolder
     * @param string $distFolder
     */
    public function __construct($sourceFolder, $distFolder)
    {
        $this->sourceFolder = $sourceFolder;
        $this->distFolder = $distFolder;
        $this->filesystem = new Filesystem();
    }

    /**
     * {@inheritdoc}
     */
    public function dump()
    {
        $this->filesystem->mirror($this->sourceFolder, $this->distFolder.DIRECTORY_SEPARATOR.uniqid('folder_'));

        return $this->distFolder;
    }

    /**
     * {@inheritdoc}
     */
    public function restore($target)
    {
        $this->filesystem->mirror($target, $this->sourceFolder);

        return true;
    }
}
