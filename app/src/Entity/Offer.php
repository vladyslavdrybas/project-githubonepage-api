<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\OfferRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OfferRepository::class, readOnly: false)]
#[ORM\Table(name: "offer")]
class Offer extends AbstractEntity
{
    #[ORM\Column(name: "title", type: Types::STRING, length: 36)]
    protected string $title;

    #[ORM\Column(name: "description", type: Types::STRING, length: 256, unique: false)]
    protected string $description;

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
}
