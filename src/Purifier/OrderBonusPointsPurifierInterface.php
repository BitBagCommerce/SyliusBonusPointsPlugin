<?php

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Purifier;

use BitBag\SyliusBonusPointsPlugin\Entity\BonusPointsInterface;

interface OrderBonusPointsPurifierInterface
{
    public function purify(BonusPointsInterface $bonusPoints): void;
}