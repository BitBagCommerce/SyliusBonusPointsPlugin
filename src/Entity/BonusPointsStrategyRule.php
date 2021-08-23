<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Entity;

class BonusPointsStrategyRule implements BonusPointsStrategyRuleInterface
{
    /** @var int */
    protected $id;

    /** @var string|null */
    protected $type;

    /** @var BonusPointsStrategyInterface|null */
    protected $bonusPointsStrategy;

    /** @var array */
    protected $configuration = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    public function getBonusPointsStrategy(): ?BonusPointsStrategyInterface
    {
        return $this->bonusPointsStrategy;
    }

    public function setBonusPointsStrategy(?BonusPointsStrategyInterface $bonusPointsStrategy): void
    {
        $this->bonusPointsStrategy = $bonusPointsStrategy;
    }

    public function getConfiguration(): array
    {
        return $this->configuration;
    }

    public function setConfiguration(array $configuration): void
    {
        $this->configuration = $configuration;
    }
}
