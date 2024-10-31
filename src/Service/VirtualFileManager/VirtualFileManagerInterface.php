<?php

namespace App\Service\VirtualFileManager;

interface VirtualFileManagerInterface
{
    public function readFromFile(): array;

    public function writeToFile(array $data): void;

    public function setDataFile(string $dataFile): self;

    public function getDataFile(): string;
}