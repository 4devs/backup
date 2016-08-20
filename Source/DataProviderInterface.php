<?php

namespace FDevs\Backup\Source;

use Symfony\Component\OptionsResolver\OptionsResolver;

interface DataProviderInterface extends DumpInterface, RestoreInterface
{
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOption(OptionsResolver $resolver);
}
