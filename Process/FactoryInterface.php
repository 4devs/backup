<?php

namespace FDevs\Backup\Process;

use Symfony\Component\Process\Process;

interface FactoryInterface
{
    /**
     * @param string $command
     *
     * @return Process
     */
    public function create($command);
}
