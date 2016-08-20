<?php

namespace FDevs\Backup\Source;

use FDevs\Backup\Exception\DataProviderException;

interface DumpInterface
{
    /**
     * @param array $options
     *
     * @return string
     *
     * @throws DataProviderException
     */
    public function dump(array $options = []);
}
