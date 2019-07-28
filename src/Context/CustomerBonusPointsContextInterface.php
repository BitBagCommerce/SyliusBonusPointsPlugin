<?php

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Context;

use BitBag\SyliusBonusPointsPlugin\Entity\CustomerBonusPointsInterface;

interface CustomerBonusPointsContextInterface
{
    public function getCustomerBonusPoints(): ?CustomerBonusPointsInterface;
}
