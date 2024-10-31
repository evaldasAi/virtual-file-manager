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

    public function testAddFile()
    {
        $this->dataHandler->setData(self::rootData());

        $this->assertEquals([], $this->dataHandler->getData()['/']);

        $this->dataHandler->addFile('/', '/actual_folder/movie.avi');
        
        $this->dataHandler->addFile('/', '/actual_folder/movie2.avi');
        
        $this->assertEquals(['movie.avi', 'movie2.avi'], $this->dataHandler->getData()['/']);

        $this->expectExceptionMessage('File already exists.');

        $this->dataHandler->addFile('/', '/actual_folder/movie2.avi');
    }

    public function testAddFolder(): void
    {
        $this->dataHandler->setData(self::exampleData());

        $this->dataHandler->createFolder('/trash/', 'trash');
        
        $this->assertArrayHasKey('/trash/trash/', $this->dataHandler->getData());

        $this->assertContains('trash/', $this->dataHandler->readDirectory('/trash/'));
    }

    public function testDeleteFolder(): void
    {
        $this->dataHandler->setData(self::exampleData());

        $this->dataHandler->deleteFolder('/downloads/movies/');

        $this->assertArrayNotHasKey('/downloads/movies/', $this->dataHandler->getData());

        $this->assertNotContains('movies/', $this->dataHandler->readDirectory('/downloads/'));
    }

    public function testRemoveFile(): void
    {
        $this->dataHandler->setData(self::exampleData());

        $this->dataHandler->removeFile("/downloads/movies/", "titanic.avi");
        
        $this->assertNotContains("titanic.avi", $this->dataHandler->readDirectory("/downloads/movies/"));
    }

    private static function rootData(): array
    {
        return ["/" => []];
    }

    private static function exampleData(): array
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