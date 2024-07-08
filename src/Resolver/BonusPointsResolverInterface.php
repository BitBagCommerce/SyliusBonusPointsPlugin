<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Resolver;

use Sylius\Component\Core\Model\Customer;
use Sylius\Component\Core\Model\OrderInterface;

interface BonusPointsResolverInterface
{
    public function resolveBonusPoints(OrderInterface $withoutOrder = null, Customer $customer = null): int;
}
