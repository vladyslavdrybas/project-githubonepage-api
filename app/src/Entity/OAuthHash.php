<?php

namespace App\Entity;

use App\Repository\OAuthHashRepository;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OAuthHashRepository::class, readOnly: false)]
#[ORM\Table(name: "oauth_hash")]
class OAuthHash implements EntityInterface
{
    #[ORM\Id]
    #[ORM\Column(name: "hash", type: Types::STRING, length: 64, unique: true, nullable: false)]
    protected string $hash;

    #[ORM\Column(name: "expire_at", type: Types::DATETIME_MUTABLE, nullable: false)]
    protected DateTimeInterface $expireAt;

    #[ORM\Column(name:'email', type: Types::STRING, length: 64, nullable: true)]
    protected ?string $email = null;

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
        return $this->getHash();
    }

    /**
     * @return string
     */
    public function getHash(): string
    {
        return $this->hash;
    }

    /**
     * @param string $hash
     */
    public function setHash(string $hash): void
    {
        $this->hash = $hash;
    }

    /**
     * @return DateTimeInterface
     */
    public function getExpireAt(): DateTimeInterface
    {
        return $this->expireAt;
    }

    /**
     * @param DateTimeInterface $expireAt
     */
    public function setExpireAt(DateTimeInterface $expireAt): void
    {
        $this->expireAt = $expireAt;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }
}
