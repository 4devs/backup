<?php

namespace FDevs\Backup\Compress;

use FDevs\Backup\Exception\ArchiveException;
use FDevs\Backup\Exception\ExtractException;
use Symfony\Component\Process\Process;

abstract class AbstractCompression implements CompressionInterface
{
    /**
     * @var null|string
     */
    private $cwd;

    /**
     * @var null|array
     */
    private $env;

    /**
     * @var null|array
     */
    private $input;

    /**
     * @var null|int|float
     */
    private $timeout;

    /**
     * @param null|string     $cwd     The working directory or null to use the working dir of the current PHP process
     * @param null|array      $env     The env variables or null to use the same environment as the current PHP process
     * @param null|mixed      $input   The input as stream resource, scalar or \Traversable, or null for no input
     * @param null|int|float  $timeout The timeout in seconds or null to disable
     */
    public function __construct($cwd = null, $env = null, $input = null, $timeout = 60)
    {
        $this->cwd = $cwd;
        $this->env = $env;
        $this->input = $input;
        $this->timeout = $timeout;
    }

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
     * @param string $command
     *
     * @return Process
     */
    private function createProcess($command)
    {
        return new Process($command, $this->cwd, $this->env, $this->input, $this->timeout);
    }
}
