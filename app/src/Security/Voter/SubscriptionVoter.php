<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\Subscription;
use App\Entity\User;
use App\Security\Permissions;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use function in_array;

class SubscriptionVoter extends AbstractVoter
{
    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!$subject instanceof Subscription) {
            return false;
        }

        if (!in_array(
            $attribute,
            [
                Permissions::READ,
            ]
        )) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        return match($attribute) {
            Permissions::READ => true,
            default => throw new \LogicException('This code should not be reached!')
        };
    }
}
