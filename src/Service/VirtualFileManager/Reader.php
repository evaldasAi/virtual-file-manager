<?php

namespace App\Service\VirtualFileManager;

class Reader
{
    private array $data = [];

    private string $dataFile = 'storage/virtual_file_storage3.json';

    public function __construct()
    {
        if (!file_exists($this->dataFile)) throw new \Exception('File doesn\'t exist.');

        $this->data = json_decode(file_get_contents($this->dataFile), true) ?? [];
    }

    public function isEmpty(string $path = '/'): bool
    {
        return true;
    }

    public function read(string $path = '/', int $depth = 0): array
    {
        return [];
    }
}