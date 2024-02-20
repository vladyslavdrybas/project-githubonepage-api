<?php

declare(strict_types=1);

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

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name:'owner_id', referencedColumnName: 'id', nullable: false)]
    protected User $owner;

    #[ORM\ManyToOne(targetEntity: Project::class)]
    #[ORM\JoinColumn(name:'project_id', referencedColumnName: 'id', nullable: false)]
    protected Project $project;

    #[ORM\Column(name: "endDate", type: Types::DATETIME_MUTABLE, nullable: true)]
    protected ?DateTimeInterface $endDate = null;

    #[ORM\Column(name: "cost_per_call", type: Types::INTEGER, nullable: false, options: ['default' => 0])]
    protected int $costPerCall = 0;

    public function __construct()
    {
        $this->setCreatedAt(new DateTime());
        $this->setUpdatedAt(new DateTime());
    }

    public function isValid(): bool
    {
        return new DateTime() <= $this->endDate;
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
     * @return \App\Entity\Project
     */
    public function getProject(): Project
    {
        return $this->project;
    }

    /**
     * @param \App\Entity\Project $project
     */
    public function setProject(Project $project): void
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
}
