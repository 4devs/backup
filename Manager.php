<?php

namespace FDevs\Backup;

use FDevs\Backup\Compress\CompressionInterface;
use FDevs\Backup\Source\DataProviderInterface;
use FDevs\Backup\Filesystem\FilesystemInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Manager
{
    /**
     * @var DataProviderInterface
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
     * @var OptionsResolver
     */
    private $resolver;

    /**
     * Manager constructor.
     *
     * @param DataProviderInterface     $source
     * @param FilesystemInterface       $filesystem
     * @param CompressionInterface|null $compressor
     */
    public function __construct(DataProviderInterface $source, FilesystemInterface $filesystem, CompressionInterface $compressor = null)
    {
        $this->source = $source;
        $this->filesystem = $filesystem;
        $this->compressor = $compressor;
        $this->local = new Filesystem();
        $this->resolver = new OptionsResolver();
        $this->source->configureOption($this->resolver);
    }

    /**
     * @param array $options
     *
     * @return string
     */
    public function dump(array $options = [])
    {
        $options = $this->resolver->resolve($options);
        $source = $this->source->dump($options);
        $file = $this->pack($source);
        $key = $this->filesystem->upload($file);
        $this->local->remove($file);
        $this->local->remove($source);

        return $key;
    }

    /**
     * @param string $key
     * @param array  $options
     *
     * @return bool
     */
    public function restore($key, array $options = [])
    {
        $options = $this->resolver->resolve($options);
        $file = $this->filesystem->download($key);
        $source = $this->unpack($file);
        $status = $this->source->restore($source, $options);
        $this->local->remove($source);
        $this->local->remove($file);

        return $status;
    }

    /**
     * @return \Iterator
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
        $target = null;
        if ($this->compressor) {
            $target = $source.$this->compressor->getExtension();
            $this->compressor->pack($source, $target);
        }

        return $target ?: $source;
    }

    /**
     * @param string $target
     *
     * @return string
     */
    private function unpack($target)
    {
        $source = null;
        if ($this->compressor) {
            $source = dirname($target).DIRECTORY_SEPARATOR.uniqid(mt_rand());
            $this->local->mkdir($source);
            $this->compressor->unpack($target, $source);
        }

        return $source ?: $target;
    }
}
