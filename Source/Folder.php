<?php

namespace FDevs\Backup\Source;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Folder extends AbstractDataProvider
{
    /**
     * @var string
     */
    private $sourceFolder;

    /**
     * @var string
     */
    private $dumpFolder;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * Folder constructor.
     *
     * @param string $sourceFolder
     * @param string $dumpFolder
     */
    public function __construct($sourceFolder, $dumpFolder)
    {
        $this->sourceFolder = $sourceFolder;
        $this->dumpFolder = $dumpFolder;
        $this->filesystem = new Filesystem();
    }

    /**
     * {@inheritdoc}
     */
    public function dump(array $options = [])
    {
        $key = $this->getDumpName($options, $this->dumpFolder);
        $this->filesystem->mirror($options['source'], $key);

        return $key;
    }

    /**
     * {@inheritdoc}
     */
    public function restore($target, array $options = [])
    {
        $this->filesystem->mirror($target, $options['source'], null, $options);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOption(OptionsResolver $resolver)
    {
        parent::configureOption($resolver);
        $resolver
            ->setDefaults([
                'source' => $this->sourceFolder,
                'prefix' => basename($this->sourceFolder),
            ])
            ->addAllowedTypes('source', ['string'])
            ->setDefined(['source'])
        ;
    }
}
