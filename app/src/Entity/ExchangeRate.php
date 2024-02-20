<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Types\CurrencyType;
use App\Repository\ExchangeRateRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use function array_pop;
use function explode;

#[ORM\Entity(repositoryClass: ExchangeRateRepository::class, readOnly: false)]
#[ORM\Table(name: "exchange_rate")]
class ExchangeRate implements EntityInterface
{
    #[ORM\Id]
    #[ORM\Column(name: "currency", type: Types::STRING, length: 3, unique: true, enumType: CurrencyType::class, options: ['default' => CurrencyType::MSC])]
    protected CurrencyType $currency = CurrencyType::MSC;

    #[ORM\Column(name: "currency_amount", type: Types::INTEGER, options: ['default' => 1])]
    protected int $currencyAmount = 1;

    #[ORM\Column(name: "credits_amount", type: Types::INTEGER, options: ['default' => 1])]
    protected int $creditsAmount;

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

    public function getRawId(): string
    {
        return $this->getCurrency()->value;
    }

    /**
     * @return \App\Entity\Types\CurrencyType
     */
    public function getCurrency(): CurrencyType
    {
        return $this->currency;
    }

    /**
     * @param \App\Entity\Types\CurrencyType $currency
     */
    public function setCurrency(CurrencyType $currency): void
    {
        $this->currency = $currency;
    }

    /**
     * @return int
     */
    public function getCurrencyAmount(): int
    {
        return $this->currencyAmount;
    }

    /**
     * @param int $currencyAmount
     */
    public function setCurrencyAmount(int $currencyAmount): void
    {
        $this->currencyAmount = $currencyAmount;
    }

    /**
     * @return int
     */
    public function getCreditsAmount(): int
    {
        return $this->creditsAmount;
    }

    /**
     * @param int $creditsAmount
     */
    public function setCreditsAmount(int $creditsAmount): void
    {
        $this->creditsAmount = $creditsAmount;
    }
}
