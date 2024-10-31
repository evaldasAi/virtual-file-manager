<?php

/**
 * Interface VirtualFileManagerInterface
 *
 * Provides methods to manage file operations in a virtual file system.
 */
namespace App\Service\VirtualFileManager;

interface VirtualFileManagerInterface
{
    /**
     * Reads data from a file and returns it as an array.
     *
     * @return array The data read from the file.
     */
    public function readFromFile(): array;

    /**
     * Writes the provided data to a file.
     *
     * @param array $data The data to write to the file.
     *
     * @return void
     */
    public function writeToFile(array $data): void;

    /**
     * Sets the path to the data file.
     *
     * @param string $dataFile The path to the data file.
     *
     * @return self Returns the current instance for method chaining.
     */
    public function setDataFile(string $dataFile): self;

    /**
     * Retrieves the path to the data file.
     *
     * @return string The path to the data file.
     */
    public function getDataFile(): string;
}