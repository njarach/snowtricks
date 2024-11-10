<?php

namespace App\Command;

use App\Entity\Trick;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

#[AsCommand(name: 'app:generate-trick-slugs', description: 'Generate slugs for existing tricks')]
class GenerateTrickSlugsCommand extends Command
{
    private EntityManagerInterface $entityManager;
    private SluggerInterface $slugger;

    public function __construct(EntityManagerInterface $entityManager, SluggerInterface $slugger)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->slugger = $slugger;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $trickRepository = $this->entityManager->getRepository(Trick::class);
        $tricks = $trickRepository->findAll();

        foreach ($tricks as $trick) {
            if (!$trick->getSlug()) {
                $trick->setSlug($this->slugger->slug($trick->getName())->lower());
            }
        }

        $this->entityManager->flush();

        $output->writeln('Slugs generated for all tricks.');
        return Command::SUCCESS;
    }
}
