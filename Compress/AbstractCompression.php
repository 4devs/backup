<?php

namespace FDevs\Backup\Compress;

use FDevs\Backup\Exception\ArchiveException;
use FDevs\Backup\Exception\ExtractException;
use Symfony\Component\Process\Process;

abstract class AbstractCompression implements CompressionInterface
{
    /**
     * @var string
     */
    private $prefix = '';

    /**
     * @var string
     */
    private $dateFormat = 'Y-m-d_H-i-s';

    /**
     * @var string
     */
    private $baseDir;

    /**
     * AbstractCompression constructor.
     *
     * @param string $baseDir
     */
    public function __construct($baseDir)
    {
        $this->baseDir = $baseDir;
    }

    /**
     * @return string
     */
    abstract protected function getPackCommand($source, $target);

    /**
     * @return string
     */
    abstract protected function getUnpackCommand($source, $target);

    /**
     * @return string
     */
    abstract protected function getExtension();

    /**
     * {@inheritdoc}
     */
    public function pack($source)
    {
        $key = dirname($source).DIRECTORY_SEPARATOR.$this->getArchiveName();
        $process = new Process($this->getPackCommand($source, $key));
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ArchiveException($process->getErrorOutput(), $process->getExitCode());
        }

        return $key;
    }

    /**
     * {@inheritdoc}
     */
    public function unpack($archive)
    {
        $key = $this->baseDir.DIRECTORY_SEPARATOR.uniqid(mt_rand());
        $process = new Process($this->getUnpackCommand($archive, $key));
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ExtractException($process->getErrorOutput(), $process->getExitCode());
        }

        return $key;
    }

    /**
     * @param string $prefix
     */
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
    }

    /**
     * @param string $dateFormat
     */
    public function setDateFormat($dateFormat)
    {
        $this->dateFormat = $dateFormat;
    }

    /**
     * @return string
     */
    protected function getArchiveName()
    {
        return $this->prefix.'_'.date($this->dateFormat).$this->getExtension();
    }
}
