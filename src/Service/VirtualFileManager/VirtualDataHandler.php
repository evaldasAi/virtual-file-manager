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

        $content = $this->readDirectory($normalizedPath);

        if (in_array($fileName, $content)) 
            throw new \Exception('File already exists.');
            
        $this->data[$normalizedPath][] = $fileName;
    }

    public function removeFile(string $path, string $fileName): void
    {
        $normalizedPath = self::normalizePath($path);

        $content = $this->readDirectory($path);

        if (!in_array($fileName, $content)) 
            throw new \Exception('File does not exist.');

        $editedContent = array_values(array_diff($content, [$fileName]));

        $this->data[$normalizedPath] = $editedContent;
    }

    public function createFolder(string $path, string $folderName): void
    {
        $normalizedPath = self::normalizePath($path);
        $normalizedFolderName = self::normalizePath($folderName);

        if ($this->readDirectory($normalizedPath))
            throw new \Exception('Folder already exists.');

        // create reference to new folder
        $this->data[$normalizedPath][] = $normalizedFolderName;

        // create actual folder
        $this->data[$normalizedPath.$normalizedFolderName] = [];
    }

    public function deleteFolder(string $path): void
    {
        $normalizedPath = self::normalizePath($path);

        if (!isset($this->data[$normalizedPath]))
            throw new \Exception('Folder doesn\'t exist.');

        // TODO check if folder is empty

        // remove reference
        $parentDirectory = dirname($normalizedPath);
        $directoryName = self::normalizePath(basename($path));
        
        $content = $this->readDirectory($parentDirectory);

        $editedContent = array_values(array_diff($content, [$directoryName]));

        $this->data[$parentDirectory] = $editedContent;

        // remove folder
        unset($this->data[$normalizedPath]);
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