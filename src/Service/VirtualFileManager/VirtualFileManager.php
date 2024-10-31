<?php

namespace App\Service\VirtualFileManager;

class VirtualFileManager implements VirtualFileManagerInterface
{
    public function __construct(private string $dataFile)
    {
        $this->setDataFile($dataFile);
    }

    /**
     * {@inheritDoc }
     */
    public function readFromFile(): array
    {
        return json_decode(file_get_contents($this->dataFile), true) ?? [];
    }

    /**
     * {@inheritdoc }
     */
    public function writeToFile(array $data): void
    {
        file_put_contents($this->dataFile, json_encode($data, JSON_PRETTY_PRINT));
    }

    /**
     * {@inheritdoc }
     */
    public function setDataFile(string $dataFile): self
    {
        if (!file_exists($dataFile)) throw new \Exception('File doesn\'t exist.');

        $this->dataFile = $dataFile;
        return $this;
    }

    /**
     * {@inheritdoc }
     */
    public function getDataFile(): string
    {
        return $this->dataFile;
    }
}
