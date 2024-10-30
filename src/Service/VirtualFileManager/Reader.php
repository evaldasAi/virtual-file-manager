<?php

namespace App\Service\VirtualFileManager;

class Reader
{
    private array $data = [];

    public function __construct(string $dataFile = null)
    {
        if (!$dataFile) throw new \Exception('File not provided.');

        if (!file_exists($dataFile)) throw new \Exception('File doesn\'t exist.');

        $this->data = json_decode(file_get_contents($dataFile), true) ?? [];
    }

    public function isEmpty(string $path = '/'): bool
    {
        return true;
    }

    public function read(string $path = '/', int $depth = 0): array
    {
        if (isset($this->data[$path])) {
            $content = $this->data[$path];
        }

        if (isset($this->data["$path/"])) {
            $content = $this->data["$path/"];
        }

        if (!isset($content)) throw new \Exception('Folder doesn\t exist.');

        return $content;
    }
}