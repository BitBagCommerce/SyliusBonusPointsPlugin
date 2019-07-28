<?php

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Resolver;

use Sylius\Component\Core\Model\OrderInterface;

interface BonusPointsResolverInterface
{
    public function resolveBonusPoints(OrderInterface $withoutOrder = null): int;
}
