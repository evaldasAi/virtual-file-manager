<?php

namespace App\Service\VirtualFileManager;

class VirtualDataHandler
{
    private array $data = [];

    public function readDirectory(string $path = '/'): array
    {
        $normalizedPath = self::normalizePath($path);

        if (!isset($this->data[$normalizedPath])) {
            throw new \Exception('Folder doesn\t exist.');
        }

        return $this->data[$normalizedPath];
    }

    public function readTree(): array
    {
        // TODO implement

        return [];
    }

    public function addFile(string $path, string $file): void
    {
        $fileName = basename($file);

        $normalizedPath = self::normalizePath($path);

        if ($this->readDirectory($normalizedPath.$fileName)) 
            throw new \Exception('File already exists.');

        $this->data[$normalizedPath][] = $fileName;
    }

    public function removeFile(): array
    {
        // TODO 
        return [];
    }

    public function createFolder(): array
    {
        // TODO 
        return [];
    }

    public function deleteFolder(): array
    {
        // TODO 
        return [];
    }

    /**
     * Currently returns only file name
     */
    public function getFile(string $filePath = '/'): ?string
    {
        $path = dirname($filePath);
        $fileName = basename($filePath);

        $content = $this->readDirectory($path);

        if (!in_array($fileName, $content)) return null;

        return $fileName;
    }

    public static function normalizePath(string $path): string
    {
        return rtrim($path, '/') . '/';
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $data = []): self
    {
        $this->data = $data;

        return $this;
    }
}