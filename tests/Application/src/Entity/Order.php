<?php

declare(strict_types=1);

namespace Tests\BitBag\SyliusBonusPointsPlugin\Entity;

use BitBag\SyliusBonusPointsPlugin\Entity\BonusPointsAwareInterface;
use BitBag\SyliusBonusPointsPlugin\Entity\BonusPointsAwareTrait;
use Sylius\Component\Core\Model\Order as BaseOrder;
use Sylius\Component\Core\Model\OrderInterface;

class Order extends BaseOrder implements OrderInterface, BonusPointsAwareInterface
{
    use BonusPointsAwareTrait;
}
