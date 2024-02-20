<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ApiKeyRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use function array_pop;
use function explode;

#[ORM\Entity(repositoryClass: ApiKeyRepository::class, readOnly: false)]
#[ORM\Table(name: "api_call_log")]
class ApiCallLog  implements EntityInterface
{
    #[ORM\Id]
    #[ORM\Column(name: "id", type: Types::STRING, length: 64, unique: true, nullable: false)]
    protected string $id;

    #[ORM\ManyToOne(targetEntity: ApiKey::class)]
    #[ORM\JoinColumn(name:'api_key_id', referencedColumnName: 'api_key')]
    protected ApiKey $apiKey;

    #[ORM\Column(name: "cost_per_call", type: Types::INTEGER)]
    protected int $costPerCall;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name:'sender_id', referencedColumnName: 'id')]
    protected User $sender;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name:'recipient_id', referencedColumnName: 'id')]
    protected User $recipient;

    #[ORM\Column(name: "created_at", type: Types::DATETIME_MUTABLE)]
    protected DateTimeInterface $createdAt;

    #[ORM\Column(name: "endpoint", type: Types::STRING, nullable: true)]
    protected ?string $endpoint = null;

    public function __construct()
    {
        $this->setCreatedAt(new DateTime());
    }

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
        return $this->getId();
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return \App\Entity\ApiKey
     */
    public function getApiKey(): ApiKey
    {
        return $this->apiKey;
    }

    /**
     * @param \App\Entity\ApiKey $apiKey
     */
    public function setApiKey(ApiKey $apiKey): void
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @return int
     */
    public function getCostPerCall(): int
    {
        return $this->costPerCall;
    }

    /**
     * @param int $costPerCall
     */
    public function setCostPerCall(int $costPerCall): void
    {
        $this->costPerCall = $costPerCall;
    }

    /**
     * @return int
     */
    public function getTax(): int
    {
        return $this->tax;
    }

    /**
     * @param int $tax
     */
    public function setTax(int $tax): void
    {
        $this->tax = $tax;
    }

    /**
     * @return \App\Entity\User
     */
    public function getSender(): User
    {
        return $this->sender;
    }

    /**
     * @param \App\Entity\User $sender
     */
    public function setSender(User $sender): void
    {
        $this->sender = $sender;
    }

    /**
     * @return \App\Entity\User
     */
    public function getRecipient(): User
    {
        return $this->recipient;
    }

    /**
     * @param \App\Entity\User $recipient
     */
    public function setRecipient(User $recipient): void
    {
        $this->recipient = $recipient;
    }

    /**
     * @return \App\Entity\User
     */
    public function getTaxer(): User
    {
        return $this->taxer;
    }

    /**
     * @param \App\Entity\User $taxer
     */
    public function setTaxer(User $taxer): void
    {
        $this->taxer = $taxer;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTimeInterface $createdAt
     */
    public function setCreatedAt(DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return string|null
     */
    public function getEndpoint(): ?string
    {
        return $this->endpoint;
    }

    /**
     * @param string|null $endpoint
     */
    public function setEndpoint(?string $endpoint): void
    {
        $this->endpoint = $endpoint;
    }
}
