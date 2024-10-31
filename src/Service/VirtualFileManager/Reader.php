<?php

namespace App\Service\VirtualFileManager;

/**
 * @deprecated
 */
class Reader
{
    private array $data = [];

    public function __construct(private string $dataFile)
    {
        if (!$dataFile) throw new \Exception('File not provided.');

        if (!file_exists($dataFile)) throw new \Exception('File doesn\'t exist.');

        $this->getData(true);
    }

    public function getData($refresh = false): array
    {
        if ($refresh){
            $this->data = json_decode(file_get_contents($this->dataFile), true) ?? [];
        }

        return $this->data;
    }

    public function isFolderEmpty(string $path = '/'): bool
    {
        return empty($this->read($path));
    }

    public function read(string $path = '/'): array
    {
        $normalizedPath = self::normalizePath($path);

        if (!isset($this->data[$normalizedPath])) {
            throw new \Exception('Folder doesn\t exist.');
        }

        return $this->data[$normalizedPath];
    }

    public static function normalizePath(string $path): string
    {
        return rtrim($path, '/') . '/';
    }

    public function readTree(string $path = '/', int $depth = 0): array
    {
        return [];
    }

    /**
     * Currently returns only file name
     */
    public function getFile(string $filePath = '/'): ?string
    {
        $path = dirname($filePath);
        $fileName = basename($filePath);

        $content = $this->read($path);

        if (in_array($fileName, $content)) return $fileName;

        return null;
    }

    public function folderExists(string $path = '/'): bool
    {
        if (isset($this->data[$path])) {
            return true;
        }

        if (isset($this->data["$path/"])) {
            return true;
        }

        return false;
    }
}