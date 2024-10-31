<?php

namespace App\Service\VirtualFileManager;

class Writer
{
    private array $data = [];

    public function __construct(private string $dataFile, private Reader $reader)
    {
        if (!$dataFile) throw new \Exception('File not provided.');

        $this->data = $reader->getData();
    }

    public function overwriteData(array $data): void
    {
        file_put_contents($this->dataFile, json_encode($data, JSON_PRETTY_PRINT));
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function createFolder(string $path): void
    {
        if ($this->reader->folderExists($path)) 
            throw new \Exception('Folder already exists.');

        $this->data[$path] = [];
    }

    public function deleteFolder(string $path): void
    {
        if ($this->reader->folderExists($path)) 
            throw new \Exception('Folder does not exist.');

        unset($this->data[$path]);
    }

    public function addFile(string $path, string $file): void
    {
        $fileName = basename($file);

        if ($this->reader->getFile($path.$fileName)) 
            throw new \Exception('File already exists.');

        $this->data[$path][] = $fileName;
    }

    public function removeFile(string $path, string $fileName): void
    {
        if (!$this->reader->getFile($path.$fileName)) 
            throw new \Exception('File does not exist.');

        unset($this->data[$path][$fileName]);
    }
}