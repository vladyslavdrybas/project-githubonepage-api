<?php

namespace App\Entity;

use App\Repository\ApiKeyRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use function array_pop;
use function explode;

#[ORM\Entity(repositoryClass: ApiKeyRepository::class, readOnly: false)]
#[ORM\Table(name: "api_key")]
class ApiKey implements EntityInterface
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\Column(name: "api_key", type: Types::STRING, length: 64, unique: true, nullable: false)]
    protected string $apiKey;

    #[ORM\Column(name: "title", type: Types::STRING, length: 36, unique: false, nullable: false)]
    protected string $title;

    #[ORM\Column(name: "owner", type: Types::STRING, length: 36, unique: false, nullable: false)]
    protected string $owner;

    #[ORM\Column(name: "project", type: Types::STRING, length: 36, unique: false, nullable: false)]
    protected string $project;

    #[ORM\Column(name: "endDate", type: Types::DATETIME_MUTABLE, nullable: true)]
    protected ?DateTimeInterface $endDate = null;

    #[ORM\Column(name: "is_subscription_active", type: Types::BOOLEAN, options: ['default' => true])]
    protected bool $isSubscriptionActive = true;

    public function __construct()
    {
        $this->setCreatedAt(new DateTime());
        $this->setUpdatedAt(new DateTime());
    }

    public function isValid(): bool
    {
        return $this->endDate >= new \DateTime();
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getEndDate(): ?DateTimeInterface
    {
        return $this->endDate;
    }

    /**
     * @param \DateTimeInterface|null $endDate
     */
    public function setEndDate(?DateTimeInterface $endDate): void
    {
        $this->endDate = $endDate;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->apiKey;
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
        return $this->getApiKey();
    }

    /**
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    /**
     * @param string $apiKey
     */
    public function setApiKey(string $apiKey): void
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @return string
     */
    public function getOwner(): string
    {
        return $this->owner;
    }

    /**
     * @param string $owner
     */
    public function setOwner(string $owner): void
    {
        $this->owner = $owner;
    }

    /**
     * @return string
     */
    public function getProject(): string
    {
        return $this->project;
    }

    /**
     * @param string $project
     */
    public function setProject(string $project): void
    {
        $this->project = $project;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return bool
     */
    public function isSubscriptionActive(): bool
    {
        return $this->isSubscriptionActive;
    }

    /**
     * @param bool $isSubscriptionActive
     */
    public function setIsSubscriptionActive(bool $isSubscriptionActive): void
    {
        $this->isSubscriptionActive = $isSubscriptionActive;
    }
}
