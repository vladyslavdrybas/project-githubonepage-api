<?php

declare(strict_types=1);

namespace App\Builder;

use App\Entity\User;
use App\Exceptions\AlreadyExists;
use App\Repository\UserRepository;
use InvalidArgumentException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use function bin2hex;
use function random_bytes;
use function sprintf;
use function strlen;
use function uniqid;

class UserBuilder implements IEntityBuilder
{
    public function __construct(
        protected readonly UserPasswordHasherInterface $passwordHasher,
        protected readonly UserRepository $userRepository
    ) {}

    public function base(
        string $email,
        string $password,
        ?string $username = null
    ): User{
        if (strlen($email) < 6) {
            throw new InvalidArgumentException('Invalid email length. Expect string length greater than 5.');
        }

        $exist = $this->userRepository->findByEmail($email);
        if ($exist instanceof User) {
            throw new AlreadyExists('Such a user already exists.');
        }

        $user = new User();

        $user->setEmail($email);
        $user->setPassword($password);

        if (null === $username) {
            $user->setUsername(sprintf(
                '%s.%s'
                , uniqid('u', false)
                , bin2hex(random_bytes(3))
            ));
        }

        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $user->getPassword()
        );

        $user->setPassword($hashedPassword);

        return $user;
    }
}
