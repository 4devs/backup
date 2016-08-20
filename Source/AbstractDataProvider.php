<?php

namespace FDevs\Backup\Source;

use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractDataProvider implements DataProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function configureOption(OptionsResolver $resolver)
    {
        $resolver
            ->setDefined(['prefix', 'date_format', 'override'])
            ->setDefaults([
                'prefix' => '',
                'override' => false,
                'date_format' => 'Y-m-d_H-i-s',
            ])
            ->setAllowedTypes('prefix', ['string'])
            ->setAllowedTypes('override', ['boolean'])
            ->setAllowedTypes('date_format', ['string'])
        ;
    }

    /**
     * @param array  $options
     * @param string $folder
     *
     * @return string
     */
    protected function getDumpName(array $options, $folder = __DIR__)
    {
        return $folder.DIRECTORY_SEPARATOR.$options['prefix'].'_'.date($options['date_format']);
    }
}
