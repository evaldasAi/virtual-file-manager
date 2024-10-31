<?php

namespace App\Command;

use App\Service\VirtualFileManager\Writer;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;


/**
 * @deprecated 
 */
#[AsCommand(
    name: 'vfm:write',
    description: 'Used to write, delete files or folders.',
)]
class VfmWriterCommand extends Command
{
    public function __construct(private Writer $writer)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('path', InputArgument::OPTIONAL, 'The folder or file path', '/')
            ->addArgument('action', InputOption::VALUE_REQUIRED, 'Actions like create, delete files and folders')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $path = $input->getArgument('path');

        $action = $input->getArgument('action');

        $file = '';

        if ($action === 'create-folder') {
            $this->writer->createFolder($path);
        }

        if ($action === 'delete-folder') {
            $this->writer->deleteFolder($path);
        }

        if ($action === 'add-file') {
            $this->writer->addFile($path, $file);
        }

        if ($action === 'delete-file') {
            $this->writer->removeFile($path, $file);
        }


        return Command::SUCCESS;
    }
}
