<?php

declare(strict_types=1);

namespace App\Command;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\UserCreditsModifier;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use function filter_var;
use const FILTER_VALIDATE_INT;

# 60*60*24*30*100 = 259200000
# 259200000 requests for each millisecond
#[AsCommand(
    name: 'user:credits:add',
    description: 'Add credits to the user.',
)]
class UserCreditsAdd extends Command
{
    public function __construct(
        protected readonly UserRepository $userRepository,
        protected readonly UserCreditsModifier $userCreditsModifier,
        string $name = null
    ) {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this
            ->addArgument(
                'userIdentifier'
                , InputArgument::REQUIRED
                , 'User identifier for whom we will generate credits.'
            )
            ->addArgument(
                'credits'
                , InputArgument::REQUIRED
                , 'Amount of credits to add.'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $userIdentifier = $input->getArgument('userIdentifier');
        $credits = $input->getArgument('credits');
        $credits = filter_var($credits, FILTER_VALIDATE_INT);

        $io = new SymfonyStyle($input, $output);

        $io->info('Adding ' . $credits . ' credits for: ' . $userIdentifier);

        $user = $this->userRepository->loadUserByIdentifier($userIdentifier);
        if (!$user instanceof User) {
            $user = $this->userRepository->find($userIdentifier);
        }

        if (!$user instanceof User) {
            $io->error('User not found by identifier.');

            return Command::FAILURE;
        }

        $io->info('User id is: ' . $user->getRawId());

        $this->userCreditsModifier->add($user, $credits);

        $io->success('Success');

        return Command::SUCCESS;
    }
}
