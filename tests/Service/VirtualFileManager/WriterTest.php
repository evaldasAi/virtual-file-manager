<?php

namespace App\Tests\Service\VirtualFileManager;

use App\Service\VirtualFileManager\Reader;
use App\Service\VirtualFileManager\Writer;
use PHPUnit\Framework\TestCase;

class WriterTest extends TestCase
{
    private ?Writer $writer = null;
    private ?Reader $reader = null;

    private const DATA_FILE_REPOSITORY = 'tests/Service/VirtualFileManager/test_data_tmp.json';

    private const DATA_FILE = 'tests/Service/VirtualFileManager/test_data_tmp.json';

    public function setUp(): void
    {
        $this->reader = new Reader(self::DATA_FILE_REPOSITORY);
        $this->writer = new Writer(self::DATA_FILE, $this->reader);
    }

    public function testCreateFile(): void
    {
        $this->writer->overwriteData([
            "/" => ['test']
        ]);

        $this->reader->getData(true);

        $this->assertEquals('test', $this->reader->getFile('/test'));
    }

}