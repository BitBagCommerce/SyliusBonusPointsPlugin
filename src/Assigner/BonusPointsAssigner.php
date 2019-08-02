<?php

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Assigner;

use BitBag\SyliusBonusPointsPlugin\Calculator\DelegatingBonusPointsStrategyCalculatorInterface;
use BitBag\SyliusBonusPointsPlugin\Checker\Eligibility\BonusPointsStrategyEligibilityCheckerInterface;
use BitBag\SyliusBonusPointsPlugin\Entity\BonusPointsInterface;
use BitBag\SyliusBonusPointsPlugin\Entity\CustomerBonusPointsInterface;
use BitBag\SyliusBonusPointsPlugin\Repository\BonusPointsStrategyRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class BonusPointsAssigner implements BonusPointsAssignerInterface
{
    /** @var DelegatingBonusPointsStrategyCalculatorInterface */
    private $delegatingBonusPointsStrategyCalculator;

    /** @var BonusPointsStrategyEligibilityCheckerInterface */
    private $bonusPointsStrategyEligibilityChecker;

    /** @var BonusPointsStrategyRepositoryInterface */
    private $bonusPointsStrategyRepository;

    /** @var RepositoryInterface */
    private $bonusPointsRepository;

    /** @var FactoryInterface */
    private $bonusPointsFactory;

    /** @var EntityManagerInterface */
    private $bonusPointsEntityManager;

    /** @var RepositoryInterface  */
    private $customerBonusPointsRepository;

    /** @var FactoryInterface */
    private $customerBonusPointsFactory;

    public function __construct(
        DelegatingBonusPointsStrategyCalculatorInterface $delegatingBonusPointsStrategyCalculator,
        BonusPointsStrategyEligibilityCheckerInterface $bonusPointsStrategyEligibilityChecker,
        BonusPointsStrategyRepositoryInterface $bonusPointsStrategyRepository,
        RepositoryInterface $bonusPointsRepository,
        FactoryInterface $bonusPointsFactory,
        EntityManagerInterface $bonusPointsEntityManager,
        RepositoryInterface $customerBonusPointsRepository,
        FactoryInterface $customerBonusPointsFactory
    ) {
        $this->delegatingBonusPointsStrategyCalculator = $delegatingBonusPointsStrategyCalculator;
        $this->bonusPointsStrategyEligibilityChecker = $bonusPointsStrategyEligibilityChecker;
        $this->bonusPointsStrategyRepository = $bonusPointsStrategyRepository;
        $this->bonusPointsRepository = $bonusPointsRepository;
        $this->bonusPointsFactory = $bonusPointsFactory;
        $this->bonusPointsEntityManager = $bonusPointsEntityManager;
        $this->customerBonusPointsRepository = $customerBonusPointsRepository;
        $this->customerBonusPointsFactory = $customerBonusPointsFactory;
    }

    public function assign(OrderInterface $order): void
    {
        $bonusPointsStrategies = $this->bonusPointsStrategyRepository->findAllActive();

        $bonusPointsTotal = 0;

        foreach ($bonusPointsStrategies as $bonusPointsStrategy) {
            if ($this->delegatingBonusPointsStrategyCalculator->isPerOrderItem($bonusPointsStrategy)) {
                /** @var OrderItemInterface $orderItem */
                foreach ($order->getItems() as $orderItem) {
                    if ($this->bonusPointsStrategyEligibilityChecker->isEligible($orderItem, $bonusPointsStrategy)) {
                        $bonusPointsTotal += $this->delegatingBonusPointsStrategyCalculator->calculate($orderItem, $bonusPointsStrategy);
                    }
                }
            } else {
                $status = true;

                /** @var OrderItemInterface $orderItem */
                foreach ($order->getItems() as $orderItem) {
                    if (!$this->bonusPointsStrategyEligibilityChecker->isEligible($orderItem, $bonusPointsStrategy)) {
                        $status = false;
                    }
                }

                if (true === $status) {
                    $bonusPointsTotal += $this->delegatingBonusPointsStrategyCalculator->calculate($order, $bonusPointsStrategy);
                }
            }
        }

        if ($bonusPointsTotal === 0) {
            return;
        }

        /** @var BonusPointsInterface $bonusPoints */
        $bonusPoints = $this->bonusPointsFactory->createNew();

        $bonusPoints->setOrder($order);
        $bonusPoints->setPoints($bonusPointsTotal);
        $bonusPoints->setIsUsed(false);
        $bonusPoints->setExpiresAt((new \DateTime())->modify('+1 year'));

        $this->getCustomerBonusPoints($order->getCustomer())->addBonusPoints($bonusPoints);

        $this->bonusPointsEntityManager->persist($bonusPoints);
        $this->bonusPointsEntityManager->flush($bonusPoints);
    }

    private function getCustomerBonusPoints(CustomerInterface $customer): CustomerBonusPointsInterface
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
}
