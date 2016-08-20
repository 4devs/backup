<?php

namespace FDevs\Backup\Filesystem;

interface ListInterface
{
    /**
     * @return \Iterator
     */
    public function keyList();
}
