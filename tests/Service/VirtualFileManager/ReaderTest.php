<?php

namespace App\Tests\Service\VirtualFileManager;

use App\Service\VirtualFileManager\Reader;
use PHPUnit\Framework\TestCase;

class ReaderTest extends TestCase
{
    private ?Reader $reader = null;

    private const DATA_FILE = 'tests/Service/VirtualFileManager/test_data.json';

    public function setUp(): void
    {
        $this->reader = new Reader(self::DATA_FILE);
    }

    public function testRead(): void
    {
        $data = $this->reader->read("/trash/");

        $this->assertEquals(0, count($data));

        $data = $this->reader->read("/downloads/images/");

        $this->assertEquals(2, count($data));

        $data = $this->reader->read("/downloads/");

        $this->assertEquals(4, count($data));
    }

    public function testPathNaming(): void
    {
        $data = $this->reader->read("/downloads");

        $this->assertEquals(4, count($data));
    }

    public function testNonExistant(): void
    {
        $this->expectExceptionMessage('Folder doesn\t exist.');

        $this->reader->read("/non-existing");
    }

    public function testIsEmpty(): void
    {
        $this->assertTrue($this->reader->isFolderEmpty("/trash/"));

        $this->assertFalse($this->reader->isFolderEmpty("/downloads/"));
    }

    public function testGetFile(): void
    {
        $this->assertEquals('img1.jpg',$this->reader->getFile("/downloads/images/img1.jpg"));
    }
}
