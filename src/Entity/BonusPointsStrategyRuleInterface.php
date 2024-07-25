<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

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
