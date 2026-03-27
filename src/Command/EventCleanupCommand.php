<?php

namespace App\Command;

use App\Repository\Platform\EventRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:event:cleanup',
    description: 'Delete past events whose startAt date is before today',
)]
class EventCleanupCommand extends Command
{
    public function __construct(
        private EventRepository $eventRepository,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $deleted = $this->eventRepository->deleteExpired();

        $io->success(sprintf('Deleted %d expired event(s).', $deleted));

        return Command::SUCCESS;
    }
}
