<?php

namespace FDevs\Backup;

use FDevs\Backup\Compress\CompressionInterface;
use FDevs\Backup\Exception\ArchiveException;
use FDevs\Backup\Exception\DataProviderException;
use FDevs\Backup\Exception\ExtractException;
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
     * @param DataProviderInterface $source
     * @param FilesystemInterface $filesystem
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
     * @throws DataProviderException
     * @throws ArchiveException
     *
     * @return string
     */
    public function dump(array $options = [])
    {
        try {
            $options = $this->resolver->resolve($options);
            $source = $this->source->dump($options);
            $file = $this->pack($source);
            $key = $this->filesystem->upload($file);
        } finally {
            if (isset($file) && $this->local->exists($file)) {
                $this->local->remove($file);
            }
            if (isset($source) && $this->local->exists($source)) {
                $this->local->remove($source);
            }
        }

        return $key;
    }

    /**
     * @param string $key
     * @param array $options
     *
     * @throws DataProviderException
     * @throws ExtractException
     *
     * @return bool
     */
    public function restore($key, array $options = [])
    {
        try {
            $options = $this->resolver->resolve($options);
            $file = $this->filesystem->download($key);
            $source = $this->unpack($file);
            $status = $this->source->restore($source, $options);
        } finally {
            if (isset($file) && $this->local->exists($file)) {
                $this->local->remove($file);
            }
            if (isset($source) && $this->local->exists($source)) {
                $this->local->remove($source);
            }
        }

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
     * @throws ArchiveException
     *
     * @return string
     */
    private function pack($source)
    {
        $target = null;
        if ($this->compressor) {
            $target = $source . $this->compressor->getExtension();
            $this->compressor->pack($source, $target);
        }

        return $target ?: $source;
    }

    /**
     * @param string $target
     *
     * @throws ExtractException
     *
     * @return string
     */
    private function unpack($target)
    {
        $source = null;
        if ($this->compressor) {
            $source = dirname($target) . DIRECTORY_SEPARATOR . uniqid(mt_rand());
            $this->local->mkdir($source);
            $this->compressor->unpack($target, $source);
        }

        return $source ?: $target;
    }
}
