<?php

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Entity;

use Sylius\Component\Resource\Model\ResourceInterface;

interface BonusPointsStrategyRuleInterface extends ResourceInterface
{
    public function getType(): ?string;

    public function setType(?string $type): void;

    public function getBonusPointsStrategy(): ?BonusPointsStrategyInterface;

    public function setBonusPointsStrategy(?BonusPointsStrategyInterface $bonusPointsStrategy): void;

    public function getConfiguration(): array;

    public function setConfiguration(array $configuration): void;
}
