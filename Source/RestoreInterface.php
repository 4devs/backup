<?php

namespace FDevs\Backup\Source;

use FDevs\Backup\Exception\DataProviderException;

interface RestoreInterface
{
    /**
     * @param string $target
     * @param array  $options
     *
     * @return bool
     *
     * @throws DataProviderException
     */
    public function restore($target, array $options = []);
}
