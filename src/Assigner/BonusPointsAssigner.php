<?php

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Assigner;

use BitBag\SyliusBonusPointsPlugin\Calculator\DelegatingBonusPointsStrategyCalculatorInterface;
use BitBag\SyliusBonusPointsPlugin\Checker\Eligibility\BonusPointsStrategyEligibilityCheckerInterface;
use BitBag\SyliusBonusPointsPlugin\Entity\AdjustmentInterface;
use BitBag\SyliusBonusPointsPlugin\Entity\BonusPointsInterface;
use BitBag\SyliusBonusPointsPlugin\Entity\BonusPointsStrategyInterface;
use BitBag\SyliusBonusPointsPlugin\Entity\CustomerBonusPointsInterface;
use BitBag\SyliusBonusPointsPlugin\Repository\BonusPointsStrategyRepositoryInterface;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Core\Distributor\ProportionalIntegerDistributorInterface;

//use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\OrderItemInterface;

//use Sylius\Component\Order\Model\OrderInterface
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use function count;

final class BonusPointsAssigner implements BonusPointsAssignerInterface
{
    /** @var DelegatingBonusPointsStrategyCalculatorInterface */
    private $delegatingBonusPointsStrategyCalculator;

    /** @var BonusPointsStrategyEligibilityCheckerInterface */
    private $bonusPointsStrategyEligibilityChecker;

    /** @var BonusPointsStrategyRepositoryInterface */
    private $bonusPointsStrategyRepository;

    /** @var FactoryInterface */
    private $bonusPointsFactory;

    /** @var EntityManagerInterface */
    private $bonusPointsEntityManager;

    /** @var RepositoryInterface */
    private $customerBonusPointsRepository;

    /** @var FactoryInterface */
    private $customerBonusPointsFactory;

    /** @var ProportionalIntegerDistributorInterface */
    private $proportionalIntegerDistributor;

    public function __construct(
        DelegatingBonusPointsStrategyCalculatorInterface $delegatingBonusPointsStrategyCalculator,
        BonusPointsStrategyEligibilityCheckerInterface $bonusPointsStrategyEligibilityChecker,
        BonusPointsStrategyRepositoryInterface $bonusPointsStrategyRepository,
        FactoryInterface $bonusPointsFactory,
        EntityManagerInterface $bonusPointsEntityManager,
        RepositoryInterface $customerBonusPointsRepository,
        FactoryInterface $customerBonusPointsFactory,
        ProportionalIntegerDistributorInterface $proportionalIntegerDistributor
    )
    {
        $this->delegatingBonusPointsStrategyCalculator = $delegatingBonusPointsStrategyCalculator;
        $this->bonusPointsStrategyEligibilityChecker = $bonusPointsStrategyEligibilityChecker;
        $this->bonusPointsStrategyRepository = $bonusPointsStrategyRepository;
        $this->bonusPointsFactory = $bonusPointsFactory;
        $this->bonusPointsEntityManager = $bonusPointsEntityManager;
        $this->customerBonusPointsRepository = $customerBonusPointsRepository;
        $this->customerBonusPointsFactory = $customerBonusPointsFactory;
        $this->proportionalIntegerDistributor = $proportionalIntegerDistributor;
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

        $this->getCustomerBonusPoints($order->getCustomer())->addBonusPoints($bonusPoints);

        $this->bonusPointsEntityManager->persist($bonusPoints);
        $this->bonusPointsEntityManager->flush();
    }

    private function getCustomerBonusPoints(?CustomerInterface $customer): CustomerBonusPointsInterface
    {
        $customerBonusPoints = $this->customerBonusPointsRepository->findOneBy([
                'customer' => $customer,
            ]) ?? $this->customerBonusPointsFactory->createNew();

        if (null === $customerBonusPoints->getId()) {
            /** @var \Sylius\Component\Core\Model\CustomerInterface|null $customer */
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

        if (count($totals) === 0) {
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
                $order->getAdjustmentsTotal(AdjustmentInterface::ORDER_BONUS_POINTS_ADJUSTMENT)
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
                    $bonusPointsStrategy->isDeductBonusPoints() && count($splitAmountOrderItems) > 0 ?
                        $splitAmountOrderItems[$orderItem->getId()] : 0
                );
            }
        }

        return $bonusPointsTotal;
    }

    /**
     * @param OrderInterface $order
     * @param BonusPointsStrategyInterface $bonusPointsStrategy
     * @param int $bonusPointsTotal
     * @return int
     */
    public function calculateBonusPoints(OrderInterface $order, BonusPointsStrategyInterface $bonusPointsStrategy, int $bonusPointsTotal): int
    {
        $status = true;

        /** @var OrderItemInterface $orderItem */
        foreach ($order->getItems() as $orderItem) {
            $product = $orderItem->getProduct();
            if (null == $product) {
                continue;
            }
            if (!$this->bonusPointsStrategyEligibilityChecker->isEligible($product, $bonusPointsStrategy)) {
                $status = false;
            }
        }

        if (true === $status) {
            $bonusPointsTotal += $this->delegatingBonusPointsStrategyCalculator->calculate(
                $order,
                $bonusPointsStrategy,
                $order->getAdjustmentsTotal(AdjustmentInterface::ORDER_BONUS_POINTS_ADJUSTMENT)
            );
        }
        return $bonusPointsTotal;
    }
}
