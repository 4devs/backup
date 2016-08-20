<?php

namespace FDevs\Backup\Compress;

use FDevs\Backup\Exception\ArchiveException;
use FDevs\Backup\Exception\ExtractException;
use Symfony\Component\Process\Process;

abstract class AbstractCompression implements CompressionInterface
{
    /**
     * @return string
     */
    abstract protected function getPackCommand($source, $target);

    /**
     * @return string
     */
    abstract protected function getUnpackCommand($source, $target);

    /**
     * {@inheritdoc}
     */
    public function pack($source, $archive)
    {
        $process = new Process($this->getPackCommand($source, $archive));
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
        $process = new Process($this->getUnpackCommand($archive, $source));
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ExtractException($process->getErrorOutput(), $process->getExitCode());
        }

        return true;
    }
}
