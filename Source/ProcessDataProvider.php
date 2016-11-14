<?php

namespace FDevs\Backup\Source;

use FDevs\Backup\Exception\DataProviderException;
use Symfony\Component\Process\Process;

abstract class ProcessDataProvider extends AbstractDataProvider
{
    /**
     * @var string
     */
    protected $outputFolder;

    /**
     * ProcessDataProvider constructor.
     *
     * @param string $outputFolder
     */
    public function __construct($outputFolder = __DIR__)
    {
        $this->outputFolder = $outputFolder;
    }

    /**
     * @param string $source
     * @param array  $options
     *
     * @return string
     */
    abstract protected function getDumpCommand($source, array $options = []);

    /**
     * @param string $target
     * @param array  $options
     *
     * @return string
     */
    abstract protected function getRestoreCommand($target, array $options = []);

    /**
     * {@inheritdoc}
     */
    public function dump(array $options = [])
    {
        $key = $this->getDumpName($options, $this->outputFolder);
        $this->execute($this->getDumpCommand($key, $options));

        return $key;
    }

    /**
     * {@inheritdoc}
     */
    public function restore($target, array $options = [])
    {
        return $this->execute($this->getRestoreCommand($target, $options));
    }

    /**
     * @param array $options
     * @param array $keys
     *
     * @return string
     */
    protected function prepareOptions(array $options = [], array $keys = [])
    {
        $cmd = '';
        foreach ($keys as $key) {
            if ($options[$key]) {
                $cmd .= ' --'.$key.($options[$key] === true ? '' : sprintf('=%s', $options[$key]));
            }
        }

        return $cmd;
    }

    /**
     * @param string $command
     *
     * @return bool
     *
     * @throws DataProviderException
     */
    private function execute($command)
    {
        $pr = new Process($command);
        $pr->run();

        if (!$pr->isSuccessful()) {
            throw new DataProviderException($pr->getErrorOutput(), $pr->getExitCode());
        }

        return true;
    }
}
