<?php

namespace FDevs\Backup\Source;

use Symfony\Component\OptionsResolver\OptionsResolver;

class MongoDB extends ProcessDataProvider
{
    /**
     * @var array
     */
    private $nullableOptions = ['username', 'password', 'db', 'collection'];

    /**
     * {@inheritdoc}
     */
    public function configureOption(OptionsResolver $resolver)
    {
        parent::configureOption($resolver);
        $resolver
            ->setDefined([
                'host',
                'port',
                'username',
                'password',
                'db',
                'collection',
            ])
            ->setAllowedTypes('host', ['string'])
            ->setAllowedTypes('port', ['string', 'int'])
            ->setAllowedTypes('username', ['string', 'null'])
            ->setAllowedTypes('password', ['string', 'null'])
            ->setAllowedTypes('db', ['string', 'null'])
            ->setAllowedTypes('collection', ['string', 'null'])
            ->setDefaults([
                'host' => 'localhost',
                'port' => 27017,
                'username' => null,
                'password' => null,
                'db' => null,
                'collection' => null,
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function getDumpCommand($source, array $options = [])
    {
        $cmd = sprintf('mongodump --host %s --port %s --out %s', $options['host'], $options['port'], $source);

        return $this->addOptions($cmd, $options);
    }

    /**
     * {@inheritdoc}
     */
    protected function getRestoreCommand($target, array $options = [])
    {
        $cmd = sprintf('mongorestore --host %s --port %s --dir %s', $options['host'], $options['port'], $target);
        if ($options['override']) {
            $cmd .= ' --drop';
        }
        var_dump($this->addOptions($cmd, $options));

        return $this->addOptions($cmd, $options);
    }

    /**
     * @param string $cmd
     * @param array  $options
     *
     * @return string
     */
    private function addOptions($cmd, array $options)
    {
        foreach ($this->nullableOptions as $item) {
            if ($options[$item]) {
                $cmd .= sprintf(' --%s %s', $item, $options[$item]);
            }
        }

        return $cmd;
    }
}
