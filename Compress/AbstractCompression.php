<?php

namespace FDevs\Backup\Compress;

use FDevs\Backup\Exception\ArchiveException;
use FDevs\Backup\Exception\ExtractException;
use FDevs\Backup\Process\FactoryInterface;
use Symfony\Component\Process\Process;

abstract class AbstractCompression implements CompressionInterface
{
    /**
     * @var FactoryInterface
     */
    private $processFactory;

    /**
     * @param string $source
     * @param string $target
     *
     * @return string
     */
    abstract protected function getPackCommand($source, $target);

    /**
     * @param string $source
     * @param string $target
     *
     * @return string
     */
    abstract protected function getUnpackCommand($source, $target);

    /**
     * {@inheritdoc}
     */
    public function pack($source, $archive)
    {
        $process = $this->createProcess($this->getPackCommand($source, $archive));
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ArchiveException($process->getErrorOutput(), $process->getExitCode());
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function unpack($archive, $source)
    {
        $process = $this->createProcess($this->getUnpackCommand($archive, $source));
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ExtractException($process->getErrorOutput(), $process->getExitCode());
        }

        return true;
    }

    /**
     * @param FactoryInterface $factory
     *
     * @return AbstractCompression
     */
    public function setProcessFactory(FactoryInterface $factory)
    {
        $this->processFactory = $factory;

        return $this;
    }

    /**
     * @param string $command
     *
     * @return Process
     */
    private function createProcess($command)
    {
        return $this->processFactory ? $this->processFactory->create($command) : new Process($command);
    }
}
