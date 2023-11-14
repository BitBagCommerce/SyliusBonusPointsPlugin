<?php

declare(strict_types=1);

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

namespace BitBag\SyliusBonusPointsPlugin\Creator;

use BitBag\SyliusBonusPointsPlugin\Entity\BonusPointsInterface;
use BitBag\SyliusBonusPointsPlugin\Entity\CustomerBonusPointsInterface;
use Sylius\Component\Core\Model\OrderInterface;

interface BonusPointsCreatorInterface
{
    public function createWithData(
        CustomerBonusPointsInterface $customerBonusPoints,
        OrderInterface $order,
        int $points,
        BonusPointsInterface $parentBonusPoints = null,
    ): BonusPointsInterface;
}
