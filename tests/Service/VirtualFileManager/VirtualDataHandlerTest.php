<?php

namespace Tests\Service\VirtualFileManager;

use App\Service\VirtualFileManager\VirtualDataHandler;
use PHPUnit\Framework\TestCase;

class VirtualDataHandlerTest extends TestCase
{
    private VirtualDataHandler $dataHandler;

    public function setUp(): void
    {
        $this->dataHandler = new VirtualDataHandler();
    }

    public function testRead(): void
    {
        $this->dataHandler->setData($this::exampleData());

        $data = $this->dataHandler->readDirectory("/trash/");

        $this->assertEquals(0, count($data));

        $data = $this->dataHandler->readDirectory("/downloads/images/");

        $this->assertEquals(2, count($data));

        $data = $this->dataHandler->readDirectory("/downloads/");

        $this->assertEquals(4, count($data));
    }

    public function testPathNaming(): void
    {
        $this->assertEquals("/downloads/", VirtualDataHandler::normalizePath("/downloads"));
    }

    public function testNonExistant(): void
    {
        $this->expectExceptionMessage('Folder doesn\t exist.');

        $this->dataHandler->readDirectory("/non-existing");
    }

    public function testGetFile(): void
    {
        $this->dataHandler->setData($this::exampleData());

        $this->assertEquals('img1.jpg',$this->dataHandler->getFile("/downloads/images/img1.jpg"));
    }

    // public function addFile()
    // {
    //     $this->dataHandler->addFile('/', '/actual_folder/movie.avi');
    // }

    public static function exampleData(): array
    {
        return [
            "/" => ["image1.txt", "image2.txt", "downloads/", "trash/"],
            "/downloads/" => ["movie.avi", "movie.mp4", "movies/", "images/"],
            "/downloads/movies/" => ["titanic.avi", "interstellar.mp4", "scooby doo [en].mkv"],
            "/downloads/images/" => ["img1.jpg", "img2.jpg"],
            "/trash/" => []
        ];
    }
}