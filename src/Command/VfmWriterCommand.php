<?php

namespace App\Command;

use App\Service\VirtualFileManager\Reader;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'vfm:write',
    description: 'Used to write, delete files or folders.',
)]
class VfmWriterCommand extends Command
{
    public function __construct(private Reader $reader)
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

        if($action === 'create-folder') // TODO

        if($action === 'delete-folder') // TODO

        if($action === 'add-file') // TODO

        if($action === 'delete-file') // TODO


        return Command::SUCCESS;
    }
}
