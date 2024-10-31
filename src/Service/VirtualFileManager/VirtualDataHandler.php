<?php

namespace App\Service\VirtualFileManager;

class VirtualDataHandler
{
    private array $data = [];

    /**
     * Reads the contents of a specified directory in the virtual file system.
     *
     * This method retrieves all files and subdirectories within the provided directory path.
     * It throws an exception if the specified path does not exist in the virtual file system.
     *
     * @param string $path The path of the directory to read. Defaults to the root directory ('/').
     * 
     * @throws \Exception If the directory does not exist in the virtual file system.
     * 
     * @return array An array containing the files and subdirectories within the specified directory.
     */
    public function readDirectory(string $path = '/'): array
    {
        $normalizedPath = self::normalizePath($path);
        
        if (!isset($this->data[$normalizedPath])) {
            throw new \Exception('Folder doesn\t exist.');
        }

        return $this->data[$normalizedPath];
    }

    /**
     * Displays structure of given path
     * 
     * @param string $path
     * @return array
     */
    public function readTree(string $path): array
    {
        // TODO complete method
        $subtree = [];

        foreach($this->data as $folderPath => $contents){
            if (strpos($folderPath, $path) === 0) {
                $subtree[$folderPath] = self::hideDirectories($contents);
            }
        }

        return $subtree;
    }

    /**
     * Uploads a virtual file to a specified directory in the virtual file system.
     * 
     * This method checks if the file already exists in the given directory path. 
     * If it does, an exception is thrown. Otherwise, it adds the file to the directory.
     *
     * @param string $path The path of the directory where the file should be added. 
     *                     This should be the virtual directory path in which the file is to be stored.
     * 
     * @param string $file The file name to add. The method will extract the base name
     *                     (file name without directory path) before adding it to the specified directory.
     * 
     * @throws \Exception If a file with the same name already exists in the directory.
     * 
     * @return void
     */
    public function addFile(string $path, string $file): void
    {
        $fileName = basename($file);

        $normalizedPath = self::normalizePath($path);

        $content = $this->readDirectory($normalizedPath);

        if (in_array($fileName, $content)) 
            throw new \Exception('File already exists.');
            
        $this->data[$normalizedPath][] = $fileName;
    }

    /**
     * Removes a virtual file from the specified directory.
     *
     * Checks if the file exists within the directory path provided. 
     * If found, it removes the file from the directory's contents.
     *
     * @param string $path The directory path from which the file should be removed.
     * @param string $fileName The name of the file to remove from the directory.
     * 
     * @throws \Exception If the specified file does not exist in the directory.
     * 
     * @return void
     */
    public function removeFile(string $path, string $fileName): void
    {
        $normalizedPath = self::normalizePath($path);

        $content = $this->readDirectory($path);

        if (!in_array($fileName, $content)) 
            throw new \Exception('File does not exist.');

        $editedContent = array_values(array_diff($content, [$fileName]));

        $this->data[$normalizedPath] = $editedContent;
    }

    /**
     * Creates a virtual folder within the specified directory.
     *
     * Checks if the folder already exists in the directory path provided.
     * If not, it adds a reference to the new folder in the parent directory and
     * initializes an empty array for the folder contents.
     *
     * @param string $path The path of the directory in which the folder should be created.
     * @param string $folderName The name of the folder to create.
     * 
     * @throws \Exception If a folder with the same name already exists in the directory.
     * 
     * @return void
     */
    public function createFolder(string $path, string $folderName): void
    {
        $normalizedPath = self::normalizePath($path);
        $normalizedFolderName = self::normalizePath($folderName);
        
        if (isset($this->data[$normalizedPath.$normalizedFolderName]))
            throw new \Exception('Folder already exists.');
            
        // create reference to new folder
        $this->data[$normalizedPath][] = $normalizedFolderName;

        // create actual folder
        $this->data[$normalizedPath.$normalizedFolderName] = [];
    }

    /**
     * Deletes a virtual folder from the specified directory.
     *
     * Checks if the folder exists in the given path. If it exists and is empty, 
     * removes both the reference in the parent directory and the folder itself.
     * 
     * @param string $path The path of the folder to delete.
     * 
     * @throws \Exception If the folder does not exist in the specified path.
     * @throws \Exception If the folder is not empty.
     * 
     * @return void
     */
    public function deleteFolder(string $path): void
    {
        $normalizedPath = self::normalizePath($path);

        if (!isset($this->data[$normalizedPath]))
            throw new \Exception('Folder doesn\'t exist.');

        // TODO check if folder is empty

        // remove reference
        $parentDirectory = self::normalizePath(dirname($path)); 
        $directoryName = self::normalizePath(basename($path));
        
        $content = $this->data[$parentDirectory];

        $editedContent = array_values(array_diff($content, [$directoryName]));

        $this->data[$parentDirectory] = $editedContent;
        
        // remove folder
        unset($this->data[$normalizedPath]);
    }

    /**
     * Retrieves the contents of a file from the specified virtual file path.
     * Currently returns only file name
     *
     * This method attempts to locate a file within the virtual file system at the given path.
     * If the file exists, it returns the file content as a string; otherwise, it returns null.
     *
     * @param string $filePath The path to the file in the virtual file system. Defaults to root ('/').
     * 
     * @return string|null The file content if the file exists, or null if the file is not found.
     */
    public function getFile(string $filePath = '/'): ?string
    {
        $path = dirname($filePath);
        $fileName = basename($filePath);

        $content = $this->readDirectory($path);

        if (!in_array($fileName, $content)) return null;

        return $fileName;
    }

    /**
     * Normalizes a directory path to ensure it has a trailing slash.
     *
     * @param string $path The directory path to normalize.
     * 
     * @return string The normalized path with a trailing slash.
     */
    public static function normalizePath(string $path): string
    {
        return rtrim($path, '/') . '/';
    }

    /**
     * Filters out directories from an array of directory contents.
     *
     * This method removes any items in the provided array that end with a trailing slash,
     * leaving only file names or non-directory entries.
     *
     * @param array $directoryContent The array of directory contents, including files and directories.
     * 
     * @return array An array containing only file names or non-directory entries.
     */
    public static function hideDirectories(array $directoryContent): array
    {
        return array_filter($directoryContent, fn($name) => substr($name, -1) !== '/');
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