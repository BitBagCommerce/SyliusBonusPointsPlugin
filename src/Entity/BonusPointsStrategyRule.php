<?php

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Entity;

class BonusPointsStrategyRule implements BonusPointsStrategyRuleInterface
{
    /** @var int */
    protected $id;

    /** @var string */
    protected $type;

    /** @var BonusPointsStrategyInterface */
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
