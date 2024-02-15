<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: ProjectRepository::class, readOnly: false)]
#[ORM\Table(name: "project")]
#[ORM\UniqueConstraint(
    name: 'subscription_project',
    columns: ['subscription_id', 'title']
)]
#[UniqueEntity(fields: ['title', 'subscription'])]
class Project extends AbstractEntity
{
    #[ORM\Column(name: "title", type: Types::STRING, length: 36, unique: false, nullable: false)]
    protected string $title;

    #[ORM\Column(name: "description", type: Types::STRING, length: 256, unique: false, nullable: true)]
    protected string $description;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name:'owner_id', referencedColumnName: 'id', nullable: false)]
    protected User $owner;

    #[ORM\ManyToOne(targetEntity: Subscription::class)]
    #[ORM\JoinColumn(name:'subscription_id', referencedColumnName: 'id', nullable: false)]
    protected Subscription $subscription;

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
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return \App\Entity\Subscription
     */
    public function getSubscription(): Subscription
    {
        return $this->subscription;
    }

    /**
     * @param \App\Entity\Subscription $subscription
     */
    public function setSubscription(Subscription $subscription): void
    {
        $this->subscription = $subscription;
    }
}
