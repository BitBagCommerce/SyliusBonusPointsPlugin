<?php

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Repository;

use Sylius\Component\Resource\Repository\RepositoryInterface;

interface BonusPointsStrategyRepositoryInterface extends RepositoryInterface
{
    public function findAllActive(): array;
}
