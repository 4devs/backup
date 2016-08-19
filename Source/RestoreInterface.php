<?php

namespace FDevs\Backup\Source;

interface RestoreInterface
{
    /**
     * @param string $target
     *
     * @return bool
     */
    public function restore($target);
}
