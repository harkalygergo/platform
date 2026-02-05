<?php

namespace App\Command;

use App\Entity\Platform\Website\Website;
use App\Controller\Platform\Backend\Website\WebsiteController;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:website:deploy',
    description: 'Deploy a website by ID',
)]
class WebsiteDeployCommand extends Command
{
    public function __construct(
        private ManagerRegistry   $doctrine,
        private WebsiteController $websiteController,
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('websiteId', InputArgument::REQUIRED, 'The website ID to deploy');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $websiteId = $input->getArgument('websiteId');

        $website = $this->doctrine->getRepository(Website::class)->find($websiteId);

        if (!$website) {
            $io->error(sprintf('Website with ID %s not found.', $websiteId));
            return Command::FAILURE;
        }

        $io->info(sprintf('Deploying website: %s', $website->getName()));

        try {
            $this->websiteController->deploy($website);
            $io->success('Website deployed successfully.');
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $io->error('Deployment failed: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
