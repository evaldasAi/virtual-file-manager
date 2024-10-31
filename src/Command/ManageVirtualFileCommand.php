<?php

namespace App\Command;

use App\Service\VirtualFileManager\VirtualDataHandler;
use App\Service\VirtualFileManager\VirtualFileManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;


#[AsCommand(
    name: 'vfm:execute',
    description: 'Used to interact with virtual files and folders',
)]
class ManageVirtualFileCommand extends Command
{
    public function __construct(
        private VirtualFileManagerInterface $virtualFileManager,
        private VirtualDataHandler $virtualDataHandler
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('action', InputOption::VALUE_REQUIRED, 'Actions like create, delete files and folders')
            ->addArgument('path', InputArgument::OPTIONAL, 'The folder or file path', '/')
            ->addArgument('name', InputArgument::OPTIONAL, 'The name of the folder or file (e.g., folder_name/, file_name.txt)', '')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $path = $input->getArgument('path');
        $action = $input->getArgument('action');
        $fileOrFolderName = $input->getArgument('name');

        $this->virtualDataHandler->setData($this->virtualFileManager->readFromFile());

        switch ($action) 
        {
            case 'read-folder':
                $result = $this->virtualDataHandler->readDirectory($path);
                break;
            case 'read-tree':
                throw new \Exception('Not implemented yet.');
                $result = $this->virtualDataHandler->readTree($path);
                break;
            case 'add-file':
                $this->virtualDataHandler->addFile($path, $fileOrFolderName);
                $result = $this->virtualDataHandler->readDirectory($path);
                break;
            case 'remove-file':
                $this->virtualDataHandler->removeFile($path, $fileOrFolderName);
                $result = $this->virtualDataHandler->readDirectory($path);
                break;
            case 'create-folder':
                $this->virtualDataHandler->createFolder($path, $fileOrFolderName);
                $result = $this->virtualDataHandler->readDirectory($path); 
                break;
            case 'delete-folder':
                $this->virtualDataHandler->deleteFolder($path);
                $result = $this->virtualDataHandler->readDirectory(dirname($path)); 
                break;
            default:
                throw new \InvalidArgumentException("Invalid action: $action");
        }
        
        $this->virtualFileManager->writeToFile($this->virtualDataHandler->getData());
        
        foreach($result as $name) {
            $io->writeln($name);
        }

        return Command::SUCCESS;
    }

}