<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Resolver;

use BitBag\SyliusBonusPointsPlugin\Context\CustomerBonusPointsContextInterface;
use Sylius\Component\Core\Model\Customer;
use Sylius\Component\Core\Model\OrderInterface;

final class BonusPointsResolver implements BonusPointsResolverInterface
{
    public function __construct(
        private readonly CustomerBonusPointsContextInterface $customerBonusPointsContext,
    ) {
    }

    public function resolveBonusPoints(
        OrderInterface $withoutOrder = null,
        Customer $customer = null,
    ): int {
        $customerPoints = $this->customerBonusPointsContext->getCustomerBonusPoints();

        if (null === $customer) {
            $customer = null !== $customerPoints ? $customerPoints->getCustomer() : null;
        }

        if (null === $customer || null === $customerPoints) {
            return 0;
        }

        $totalAvailablePoints = 0;

        foreach ($customerPoints->getBonusPoints() as $customerPoint) {
            if (!$customerPoint->isExpired()) {
                $totalAvailablePoints += $customerPoint->getLeftPointsFromAvailablePool($withoutOrder);
            }
        }

        return $totalAvailablePoints;
    }
}
