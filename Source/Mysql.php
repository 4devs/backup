<?php

namespace FDevs\Backup\Source;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Mysql extends ProcessDataProvider
{
    /**
     * {@inheritdoc}
     */
    protected function getDumpCommand($source, array $options = [])
    {
        $optional = $this->prepareOptions($options, $this->getDumpOptions());
        $optional .= count($options['ignore-table']) ? sprintf(' --ignore-table=%s', implode(' --ignore-table=', $options['ignore-table'])) : '';
        $optional .= $options['where'] ? sprintf(" --where='%s'", $options['where']) : '';
        $optional .= count($options['databases']) ? sprintf(' --databases %s', implode(' ', $options['databases'])) : '';
        $optional .= count($options['tables']) ? sprintf(' --tables %s', implode(' ', $options['tables'])) : '';

        if (!is_dir($source)) {
            mkdir($source);
        }

        return sprintf('mysqldump --host=%s --port=%s %s > %s/%s', $options['host'], $options['port'], $optional, $source, $options['filename']);
    }

    /**
     * @return array
     */
    protected function getDumpOptions()
    {
        return [
            'all-databases',
            'create-options',
            'delayed-insert',
            'disable-keys',
            'force',
            'extended-insert',
            'add-drop-database',
            'add-drop-table',
            'no-create-db',
            'no-create-info',
            'no-data',
            'skip-opt',
            'quick',
            'lock-tables',
            'password',
            'user',
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getRestoreCommand($target, array $options = [])
    {
        $optional = $options['user'] ? sprintf('--user=%s ', $options['user']) : '';
        $optional .= $options['password'] ? sprintf('-p%s ', $options['password']) : '';
        $optional .= count($options['databases']) == 1 ? implode('', $options['databases']) : '';
        $cmd = sprintf('mysql --host=%s --port=%s %s < %s/%s', $options['host'], $options['port'], $optional, $target, $options['filename']);

        return $cmd;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOption(OptionsResolver $resolver)
    {
        parent::configureOption($resolver);

        $resolver
            ->setRequired(['host', 'port'])
            ->setDefined(array_merge($this->getDumpOptions(), ['where', 'databases', 'ignore-table', 'tables', 'filename']))
            ->setDefaults([
                'port' => 3306,
                'host' => 'localhost',
                'user' => null,
                'password' => null,
                'where' => '',
                'databases' => [],
                'ignore-table' => [],
                'tables' => [],
                'filename' => function (Options $options) {
                    $names = array_merge($options['databases'], $options['tables'], $options['ignore-table']);

                    return (count($names) ? implode('-', $names) : 'all-databases') . '.sql';
                },
                'all-databases' => function (Options $options) {
                    return !count($options['databases']);
                },
                'create-options' => false,
                'extended-insert' => false,
                'delayed-insert' => false,
                'disable-keys' => false,
                'force' => false,
                'no-create-db' => false,
                'no-create-info' => false,
                'no-data' => false,
                'skip-opt' => false,
                'quick' => false,
                'lock-tables' => false,
                'add-drop-database' => function (Options $options) {
                    return $options['override'];
                },
                'add-drop-table' => function (Options $options) {
                    return $options['override'];
                },
            ])
            ->setAllowedTypes('databases', ['array'])
            ->setAllowedTypes('tables', ['array'])
            ->setAllowedTypes('ignore-table', ['array'])
            ->setAllowedTypes('lock-tables', ['boolean', 'string'])
            ->setAllowedTypes('where', ['string'])
            ->setAllowedTypes('filename', ['string'])
            ->setAllowedTypes('port', ['integer'])
            ->setAllowedTypes('host', ['string'])
            ->setAllowedTypes('password', ['string', 'null'])
            ->setAllowedTypes('user', ['string', 'null'])
            ->setAllowedTypes('all-databases', ['boolean'])
            ->setAllowedTypes('add-drop-database', ['boolean', 'string'])
            ->setAllowedTypes('add-drop-table', ['boolean', 'string'])
            ->setAllowedTypes('create-options', ['boolean', 'string'])
            ->setAllowedTypes('delayed-insert', ['boolean', 'string'])
            ->setAllowedTypes('force', ['boolean', 'string'])
            ->setAllowedTypes('extended-insert', ['boolean', 'string'])
            ->setAllowedTypes('no-create-db', ['boolean', 'string'])
            ->setAllowedTypes('no-create-info', ['boolean', 'string'])
            ->setAllowedTypes('no-data', ['boolean', 'string'])
            ->setAllowedTypes('skip-opt', ['boolean'])
            ->setAllowedTypes('quick', ['boolean', 'string'])
            ->setAllowedTypes('disable-keys', ['boolean', 'string']);
    }
}
