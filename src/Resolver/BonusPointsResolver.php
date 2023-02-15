<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Resolver;

use BitBag\SyliusBonusPointsPlugin\Context\CustomerBonusPointsContextInterface;
use Sylius\Component\Core\Model\Customer;
use Sylius\Component\Core\Model\OrderInterface;

final class BonusPointsResolver implements BonusPointsResolverInterface
{
    /** @var CustomerBonusPointsContextInterface */
    private $customerBonusPointsContext;

    public function __construct(
        CustomerBonusPointsContextInterface $customerBonusPointsContext
    ) {
        $this->customerBonusPointsContext = $customerBonusPointsContext;
    }

    public function resolveBonusPoints(
        OrderInterface $withoutOrder = null,
        Customer $customer = null
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
            if (null === $withoutOrder || $customerPoint->getOrder() !== $withoutOrder) {
                $totalAvailablePoints += $customerPoint->getLeftPointsFromAvailablePool();
            }
        }

        return $totalAvailablePoints;
    }
}
