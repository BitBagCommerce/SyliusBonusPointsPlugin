<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Assigner;

use BitBag\SyliusBonusPointsPlugin\Calculator\DelegatingBonusPointsStrategyCalculatorInterface;
use BitBag\SyliusBonusPointsPlugin\Checker\Eligibility\BonusPointsStrategyEligibilityCheckerInterface;
use BitBag\SyliusBonusPointsPlugin\Entity\AdjustmentInterface;
use BitBag\SyliusBonusPointsPlugin\Entity\BonusPointsInterface;
use BitBag\SyliusBonusPointsPlugin\Entity\BonusPointsStrategyInterface;
use BitBag\SyliusBonusPointsPlugin\Entity\CustomerBonusPointsInterface;
use BitBag\SyliusBonusPointsPlugin\Repository\BonusPointsStrategyRepositoryInterface;
use function count;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Core\Distributor\ProportionalIntegerDistributorInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class BonusPointsAssigner implements BonusPointsAssignerInterface
{
    public function __construct(
        private DelegatingBonusPointsStrategyCalculatorInterface $delegatingBonusPointsStrategyCalculator,
        private BonusPointsStrategyEligibilityCheckerInterface $bonusPointsStrategyEligibilityChecker,
        private BonusPointsStrategyRepositoryInterface $bonusPointsStrategyRepository,
        private FactoryInterface $bonusPointsFactory,
        private EntityManagerInterface $bonusPointsEntityManager,
        private RepositoryInterface $customerBonusPointsRepository,
        private FactoryInterface $customerBonusPointsFactory,
        private ProportionalIntegerDistributorInterface $proportionalIntegerDistributor,
    ) {
    }

    public function assign(OrderInterface $order): void
    {
        $bonusPointsStrategies = $this->bonusPointsStrategyRepository->findAllActive();

        $bonusPointsTotal = 0;

        /** @var BonusPointsStrategyInterface $bonusPointsStrategy */
        foreach ($bonusPointsStrategies as $bonusPointsStrategy) {
            if ($this->delegatingBonusPointsStrategyCalculator->isPerOrderItem($bonusPointsStrategy)) {
                $bonusPointsTotal = $this->calculateBonusPointsPerOrder($bonusPointsStrategy, $order, $bonusPointsTotal);
            } else {
                $bonusPointsTotal = $this->calculateBonusPoints($order, $bonusPointsStrategy, $bonusPointsTotal);
            }
        }

        if (0 === $bonusPointsTotal) {
            return;
        }

        /** @var BonusPointsInterface $bonusPoints */
        $bonusPoints = $this->bonusPointsFactory->createNew();
        $bonusPoints->setOrder($order);
        $bonusPoints->setPoints($bonusPointsTotal);
        $bonusPoints->setIsUsed(false);

        //TODO :: Change to more flexible date ?
        $bonusPoints->setExpiresAt((new DateTime())->modify('+1 year'));

        /** @var CustomerInterface $customer */
        $customer = $order->getCustomer();
        $this->getCustomerBonusPoints($customer)->addBonusPoints($bonusPoints);

        $this->bonusPointsEntityManager->persist($bonusPoints);
        $this->bonusPointsEntityManager->flush();
    }

    private function getCustomerBonusPoints(?CustomerInterface $customer): CustomerBonusPointsInterface
    {
        /** @var CustomerBonusPointsInterface $customerBonusPoints */
        $customerBonusPoints = $this->customerBonusPointsRepository->findOneBy([
                'customer' => $customer,
            ]) ?? $this->customerBonusPointsFactory->createNew();

        if (null === $customerBonusPoints->getId()) {
            $customerBonusPoints->setCustomer($customer);

            $this->customerBonusPointsRepository->add($customerBonusPoints);
        }

        return $customerBonusPoints;
    }

    private function getProportionalIntegerToDeduct(BonusPointsStrategyInterface $bonusPointsStrategy, array $orderItems, int $bonusPointsAmount): array
    {
        /** @var OrderItemInterface[] $itemsEligible */
        $itemsEligible = [];

        foreach ($orderItems as $orderItem) {
            $product = $orderItem->getProduct();

            if ($this->bonusPointsStrategyEligibilityChecker->isEligible($product, $bonusPointsStrategy)) {
                $itemsEligible[] = $orderItem;
            }
        }

        $totals = [];

        foreach ($itemsEligible as $item) {
            $totals[] = $item->getTotal();
        }

        if (0 === count($totals)) {
            return [];
        }

        $splitAmount = $this->proportionalIntegerDistributor->distribute($totals, $bonusPointsAmount);

        $splitAmountOrderItems = [];

        $i = 0;

        foreach ($itemsEligible as $eligible) {
            $splitAmountOrderItems[$eligible->getId()] = $splitAmount[$i++];
        }

        return $splitAmountOrderItems;
    }

    public function calculateBonusPointsPerOrder(BonusPointsStrategyInterface $bonusPointsStrategy, OrderInterface $order, int $bonusPointsTotal): int
    {
        $splitAmountOrderItems = [];
        if ($bonusPointsStrategy->isDeductBonusPoints()) {
            $splitAmountOrderItems = $this->getProportionalIntegerToDeduct(
                $bonusPointsStrategy,
                $order->getItems()->toArray(),
                $order->getAdjustmentsTotal(AdjustmentInterface::ORDER_BONUS_POINTS_ADJUSTMENT),
            );
        }

        /** @var OrderItemInterface $orderItem */
        foreach ($order->getItems() as $orderItem) {
            $product = $orderItem->getProduct();

            if (null == $product) {
                continue;
            }

            if ($this->bonusPointsStrategyEligibilityChecker->isEligible($product, $bonusPointsStrategy)) {
                $bonusPointsTotal += $this->delegatingBonusPointsStrategyCalculator->calculate(
                    $orderItem,
                    $bonusPointsStrategy,
                    $bonusPointsStrategy->isDeductBonusPoints() && 0 < count($splitAmountOrderItems) ?
                        $splitAmountOrderItems[$orderItem->getId()] : 0,
                );
            }
        }

        return $bonusPointsTotal;
    }

    public function calculateBonusPoints(OrderInterface $order, BonusPointsStrategyInterface $bonusPointsStrategy, int $bonusPointsTotal): int
    {
        /** @var OrderItemInterface[] $eligibleOrderItems */
        $eligibleOrderItems = [];

        /** @var OrderItemInterface $orderItem */
        foreach ($order->getItems() as $orderItem) {
            $product = $orderItem->getProduct();
            if (null == $product) {
                continue;
            }
            if (!$this->bonusPointsStrategyEligibilityChecker->isEligible($product, $bonusPointsStrategy)) {
                $eligibleOrderItems[] = $orderItem;
            }
        }

        foreach ($eligibleOrderItems as $eligibleProduct) {
            $bonusPointsTotal += $this->delegatingBonusPointsStrategyCalculator->calculate(
                $eligibleProduct,
                $bonusPointsStrategy,
                $order->getAdjustmentsTotal(AdjustmentInterface::ORDER_BONUS_POINTS_ADJUSTMENT),
            );
        }

        return $bonusPointsTotal;
    }
}
