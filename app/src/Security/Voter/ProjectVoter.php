<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\Project;
use App\Entity\User;
use App\Security\Permissions;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use function in_array;

class ProjectVoter extends AbstractVoter
{
    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!$subject instanceof Project) {
            return false;
        }

        if (!in_array(
            $attribute,
            [
                Permissions::READ,
                Permissions::UPDATE,
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
            Permissions::READ => $this->canRead($subject, $user),
            Permissions::UPDATE => $this->canUpdate($subject, $user),
            default => throw new \LogicException('This code should not be reached!')
        };
    }

    protected function canRead(Project $subject, User $user): bool
    {
        return $this->isOwner($subject, $user);
    }

    protected function canUpdate(Project $subject, User $user): bool
    {
        return $this->isOwner($subject, $user);
    }
}
