<?php

declare(strict_types=1);

namespace App\Command;
use App\Entity\Offer;
use App\Repository\OfferRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'subscription:offer:add',
    description: 'Add new subscription offer.',
)]
class SubscriptionOfferAdd extends Command
{
    public function __construct(
        protected readonly OfferRepository $offerRepository,
        string $name = null
    ) {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this
            ->addArgument(
                'title'
                , InputArgument::REQUIRED
                , 'Title of the offer.'
            )
            ->addArgument(
                'description'
                , InputArgument::OPTIONAL
                , 'Description of the offer.'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $title = $input->getArgument('title');
        $description = $input->getArgument('description');

        $io = new SymfonyStyle($input, $output);

        $offer = new Offer();
        $offer->setTitle($title);
        $offer->setDescription($description);

        $this->offerRepository->add($offer);
        $this->offerRepository->save();

        $io->info('New offer created' . $offer->getRawId());

        $io->success('Success');

        return Command::SUCCESS;
    }
}
