<?php

namespace FDevs\Backup\Filesystem;

interface ListInterface
{
    /**
     * @return array|string[]
     */
    public function keyList();
}
