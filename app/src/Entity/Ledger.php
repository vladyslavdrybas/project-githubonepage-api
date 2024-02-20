<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ApiKeyRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use function array_pop;
use function explode;

#[ORM\Entity(repositoryClass: ApiKeyRepository::class, readOnly: false)]
#[ORM\Table(name: "ledger")]
class Ledger implements EntityInterface
{
    #[ORM\Id]
    #[ORM\OneToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name:'owner_id', referencedColumnName: 'id')]
    protected User $owner;

    #[ORM\Column(name: "balance", type: Types::INTEGER, nullable: false, options: ['default' => 0])]
    protected int $balance = 0;

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getRawId();
    }

    /**
     * @return string
     */
    public function getObject(): string
    {
        $namespace = explode('\\', static::class);

        return array_pop($namespace);
    }

    /**
     * @return string
     */
    public function getRawId(): string
    {
        return $this->owner->getRawId();
    }

    /**
     * @return \App\Entity\User
     */
    public function getOwner(): User
    {
        return $this->owner;
    }

    /**
     * @param \App\Entity\User $owner
     */
    public function setOwner(User $owner): void
    {
        $this->owner = $owner;
    }

    /**
     * @return int
     */
    public function getBalance(): int
    {
        return $this->balance;
    }

    /**
     * @param int $balance
     */
    public function setBalance(int $balance): void
    {
        $this->balance = $balance;
    }
}