<?php

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Resolver;

use BitBag\SyliusBonusPointsPlugin\Entity\BonusPoints;
use BitBag\SyliusBonusPointsPlugin\Repository\BonusPointsRepository;
use Sylius\Bundle\CoreBundle\Context\CustomerContext;
use Sylius\Component\Core\Model\Customer;
use Sylius\Component\Core\Model\OrderInterface;

final class BonusPointsResolver implements BonusPointsResolverInterface
{
    /** @var BonusPointsRepository */
    private $bonusPointsRepo;

    /** @var array */
    private $currentBonusPoints;

    /** @var CustomerContext */
    private $customerContext;

    public function __construct(BonusPointsRepository $bonusPointsRepo, CustomerContext $customerContext)
    {
        $this->bonusPointsRepo = $bonusPointsRepo;
        $this->customerContext = $customerContext;
    }

    public function resolveBonusPoints(OrderInterface $withoutOrder = null, Customer $customer = null): int
    {
        if (null === $customer) {
            $customer = $this->customerContext->getCustomer();
        }

        if (null === $customer) {
            return 0;
        }

        $this->currentBonusPoints = [];
        $customerPoints = $this->bonusPointsRepo->findAllCustomerPointsMovements($customer);

        foreach ($customerPoints as $customerPoint) {
            if (!$customerPoint->isUsed()) {
                $this->addBonusPoints($customerPoint);

                continue;
            }

            if (null === $withoutOrder || $customerPoint->getOrder() !== $withoutOrder) {
                $this->removeBonusPoints($customerPoint);
            }
        }

        $this->checkExpiredBonusPoints(new \DateTime());

        return (int) array_sum(array_column($this->currentBonusPoints, 'points'));
    }

    /**
     * Check if bonus points earned ealier are still available. Unsets the expired entries.
     */
    public function checkExpiredBonusPoints(\DateTime $currentDate): void
    {
        foreach ($this->currentBonusPoints as $key => $currentBonusPoint) {
            if ($currentBonusPoint['expireDate'] < $currentDate) {
                unset($this->currentBonusPoints[$key]);
            }
        }
    }

    /**
     * Add earned bonus points to the available points pool.
     */
    public function addBonusPoints(BonusPoints $bonusPoints): void
    {
        $this->currentBonusPoints[] = ['points' => $bonusPoints->getPoints(), 'expireDate' => $bonusPoints->getExpiresAt()];
    }

    /**
     * Remove spent bonus points to the available points pool starting by the most ancient ones. Unsets entries that are null
     * after removing all or part of the amount to deduce.
     */
    public function removeBonusPoints(BonusPoints $bonusPoints): void
    {
        $this->checkExpiredBonusPoints($bonusPoints->getUpdatedAt());
        $this->deducePointsFromEntry($bonusPoints->getPoints());
    }

    /**
     * Function that recursively looks for the bonus points entry with the closest expireDate to deduce used points.
     */
    public function deducePointsFromEntry(int $pointsToDeduce): void
    {
        $closestBonusPointsToExpire = $this->searchClosestToExpireBonusPoints();
        $remainingPointsToDeduce = (int) ($pointsToDeduce - $closestBonusPointsToExpire['bonusPoints']['points']);

        // if the current BonusPoint has more points, deduce from it and return
        if ($remainingPointsToDeduce < 0) {
            $this->currentBonusPoints[$closestBonusPointsToExpire['offset']]['points'] -= $pointsToDeduce;

            return;
        }

        // Remove this BonusPoint from the remaining array has all its points will be burned
        unset($this->currentBonusPoints[$closestBonusPointsToExpire['offset']]);

        if (0 === $remainingPointsToDeduce) {
            return;
        }

        // else continue deduce points from the next BonusPoint available
        $this->deducePointsFromEntry($remainingPointsToDeduce);
    }

    /**
     * Searches the bonus points entry with the closest expire date. Necessary if the validity period of the bonus points can * vary.
     */
    public function searchClosestToExpireBonusPoints(): array
    {
        $closestToExpire = [];

        foreach ($this->currentBonusPoints as $offset => $bonusPoints) {
            if (!isset($closestToExpire['bonusPoints']) || $bonusPoints['expireDate'] < $closestToExpire['bonusPoints']['expireDate']) {
                $closestToExpire = ['offset' => $offset, 'bonusPoints' => $bonusPoints];
            }
        }

        return $closestToExpire;
    }
}
