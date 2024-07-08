<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
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
