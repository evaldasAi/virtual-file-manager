<?php

namespace App\Tests\VirtualFileManager;

use App\Service\VirtualFileManager\Reader;
use PHPUnit\Framework\TestCase;

class ReaderTest extends TestCase
{
    private ?Reader $reader = null;

    public function setUp(): void
    {
        $this->reader = new Reader();
    }

    public function testIsEmpty(): void
    {
        $this->assertTrue($this->reader->isEmpty());
    }
}
