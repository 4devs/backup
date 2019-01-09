<?php

namespace FDevs\Backup\Process;

use Symfony\Component\Process\Process;

class Factory implements FactoryInterface
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
     * @param null|string    $cwd     The working directory or null to use the working dir of the current PHP process
     * @param null|array     $env     The env variables or null to use the same environment as the current PHP process
     * @param null|mixed     $input   The input as stream resource, scalar or \Traversable, or null for no input
     * @param null|int|float $timeout The timeout in seconds or null to disable
     */
    public function __construct($cwd = null, $env = null, $input = null, $timeout = 60)
    {
        $this->cwd = $cwd;
        $this->env = $env;
        $this->input = $input;
        $this->timeout = $timeout;
    }

    /**
     * {@inheritdoc}
     */
    public function create($command)
    {
        return new Process($command, $this->cwd, $this->env, $this->input, $this->timeout);
    }
}
