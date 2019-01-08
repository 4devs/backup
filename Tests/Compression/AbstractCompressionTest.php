<?php

namespace FDevs\Backup\Tests\Compression;

use FDevs\Backup\Compress\AbstractCompression;
use FDevs\Backup\Exception\ArchiveException;
use FDevs\Backup\Exception\ExtractException;
use FDevs\Backup\Process\FactoryInterface;
use FDevs\Backup\Tests\AbstractTest;
use Symfony\Component\Process\Process;

abstract class AbstractCompressionTest extends AbstractTest
{
    /**
     * @return string
     */
    abstract protected function exceptedExtension();

    /**
     * @return AbstractCompression
     */
    abstract protected function getInstance();

    /**
     * test extension.
     */
    public function testExtension()
    {
        $this->assertSame($this->exceptedExtension(), $this->getInstance()->getExtension());
    }

    /**
     * test pack.
     */
    public function testPack()
    {
        $process = $this->getProcessMock([
            'run' => 0,
            'isSuccessful' => true,
        ]);

        $processFactory = $this->getProcessFactoryMock([
            'create' => $process,
        ]);

        $compression = $this
            ->getInstance()
            ->setProcessFactory($processFactory)
        ;

        $success = $compression->pack('source', 'archive');

        $this->assertTrue($success);
    }

    /**
     * test pack fail.
     */
    public function testPackFail()
    {
        $process = $this->getProcessMock([
            'run' => 1,
            'isSuccessful' => false,
        ]);

        $processFactory = $this->getProcessFactoryMock([
            'create' => $process,
        ]);

        $compression = $this
            ->getInstance()
            ->setProcessFactory($processFactory)
        ;

        try {
            $compression->pack('source', 'archive');
            $this->fail('Expect ArchiveException::class exception, but that not thrown');
        } catch (\Exception $e) {
            $this->assertInstanceOf(ArchiveException::class, $e);
        }
    }

    /**
     * test unpack.
     */
    public function testUnpack()
    {
        $process = $this->getProcessMock([
            'run' => 0,
            'isSuccessful' => true,
        ]);

        $processFactory = $this->getProcessFactoryMock([
            'create' => $process,
        ]);

        $compression = $this
            ->getInstance()
            ->setProcessFactory($processFactory)
        ;

        $success = $compression->unpack('archive','source');

        $this->assertTrue($success);
    }

    /**
     * test unpack fail.
     */
    public function testUnpackFail()
    {
        $process = $this->getProcessMock([
            'run' => 1,
            'isSuccessful' => false,
        ]);

        $processFactory = $this->getProcessFactoryMock([
            'create' => $process,
        ]);

        $compression = $this
            ->getInstance()
            ->setProcessFactory($processFactory)
        ;

        try {
            $compression->unpack('archive','source');
            $this->fail('Expect ExtractException::class exception, but that not thrown');
        } catch (\Exception $e) {
            $this->assertInstanceOf(ExtractException::class, $e);
        }
    }

    /**
     * @param array $configuration
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|Process
     */
    protected function getProcessMock(array $configuration = [])
    {
        return $this->createConfiguredMock(Process::class, $configuration);
    }

    /**
     * @param array $configuration
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|FactoryInterface
     */
    protected function getProcessFactoryMock(array $configuration = [])
    {
        return $this->createConfiguredMock(FactoryInterface::class, $configuration);
    }
}


