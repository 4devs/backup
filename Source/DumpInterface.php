<?php

namespace FDevs\Backup\Source;

interface DumpInterface
{
    /**
     * @return string filename
     */
    public function dump();
}
