<?php

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Entity;

interface BonusPointsAwareInterface
{
    public function getBonusPoints(): ?int;

    public function setBonusPoints(?int $bonusPoints): void;
}
