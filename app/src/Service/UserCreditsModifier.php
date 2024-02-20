<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;

class UserCreditsModifier
{
    public function __construct(
        protected readonly UserRepository $userRepository,
    ) {
    }

    public function add(User $user, int $credits): void
    {
        $user->getLedger()->setBalance($user->getLedger()->getBalance() + $credits);

        $this->userRepository->add($user);
        $this->userRepository->save();
    }
}
