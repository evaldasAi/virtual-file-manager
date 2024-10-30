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
    name: 'vfm:read',
    description: 'Used to read virtual files and folders',
)]
class VfmReaderCommand extends Command
{
    public function __construct(private Reader $reader)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('path', InputArgument::OPTIONAL, 'The folder or file path', '/')
            ->addOption('depth', null, InputOption::VALUE_OPTIONAL, 'Option description', 0)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $path = $input->getArgument('path');

        $depth = $input->getOption('depth');

        try {
            $info = $this->reader->read($path, $depth);
        } catch(\Exception $exception){
            $io->error($exception->getMessage());

            // do other things, for example log message

            return Command::FAILURE;
        }

        // Render $info

        return Command::SUCCESS;
    }
}
