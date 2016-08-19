<?php

namespace FDevs\Backup;

use FDevs\Backup\Compress\CompressionInterface;
use FDevs\Backup\Source\SourceInterface;
use FDevs\Backup\Filesystem\FilesystemInterface;
use Symfony\Component\Filesystem\Filesystem;

class Manager
{
    /**
     * @var SourceInterface
     */
    private $source;

    /**
     * @var FilesystemInterface
     */
    private $filesystem;

    /**
     * @var CompressionInterface|null
     */
    private $compressor;

    /**
     * @var Filesystem
     */
    private $local;

    /**
     * Manager constructor.
     *
     * @param SourceInterface           $source
     * @param FilesystemInterface       $filesystem
     * @param CompressionInterface|null $compressor
     */
    public function __construct(SourceInterface $source, FilesystemInterface $filesystem, CompressionInterface $compressor = null)
    {
        $this->source = $source;
        $this->filesystem = $filesystem;
        $this->compressor = $compressor;
        $this->local = new Filesystem();
    }

    /**
     * @return string
     */
    public function dump()
    {
        $source = $this->source->dump();
        $file = $this->pack($source);
        $key = $this->filesystem->upload($file);
        $this->local->remove($file);
        $this->local->remove($source);

        return $key;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function restore($key)
    {
        $file = $this->filesystem->download($key);
        $source = $this->unpack($file);

        $status = $this->source->restore($source);
        $this->local->remove($source);
        $this->local->remove($file);

        return $status;
    }

    /**
     * @return array|\string[]
     */
    public function keyList()
    {
        return $this->filesystem->keyList();
    }

    /**
     * @param string $source
     *
     * @return string
     */
    private function pack($source)
    {
        return $this->compressor ? $this->compressor->pack($source) : $source;
    }

    /**
     * @param string $target
     *
     * @return string
     */
    private function unpack($target)
    {
        return $this->compressor ? $this->compressor->unpack($target) : $target;
    }
}
