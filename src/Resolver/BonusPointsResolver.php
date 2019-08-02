<?php

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Resolver;

use BitBag\SyliusBonusPointsPlugin\Context\CustomerBonusPointsContextInterface;
use BitBag\SyliusBonusPointsPlugin\Entity\BonusPointsInterface;
use Sylius\Component\Core\Model\OrderInterface;

final class BonusPointsResolver implements BonusPointsResolverInterface
{
    /** @var CustomerBonusPointsContextInterface */
    private $customerBonusPointsContext;

    public function __construct(CustomerBonusPointsContextInterface $customerBonusPointsContext)
    {
        $this->customerBonusPointsContext = $customerBonusPointsContext;
    }

    public function resolveBonusPoints(OrderInterface $withoutOrder = null): int
    {
        $customerBonusPoints = $this->customerBonusPointsContext->getCustomerBonusPoints();

        if (null == $customerBonusPoints) {
            return 0;
        }

        $bonusPointsTotal = 0;

        /** @var BonusPointsInterface $bonusPoints */
        foreach ($customerBonusPoints->getBonusPoints() as $bonusPoints) {
            if ($bonusPoints->getExpiresAt() >= (new \DateTime())) {
                $bonusPointsTotal += $bonusPoints->getPoints();
            }
        }

        if ($bonusPointsTotal === 0) {
            return 0;
        }

        /** @var BonusPointsInterface $bonusPointsUsed */
        foreach ($customerBonusPoints->getBonusPointsUsed() as $bonusPointsUsed) {
            if (null === $withoutOrder || (null !== $withoutOrder && $withoutOrder !== $bonusPointsUsed->getOrder())) {
                $bonusPointsTotal -= $bonusPointsUsed->getPoints();
            }
        }

        return $bonusPointsTotal > 0 ? $bonusPointsTotal : 0;
    }
}
