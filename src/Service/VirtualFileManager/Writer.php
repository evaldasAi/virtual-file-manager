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
}