<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\OrderProcessing;

use BitBag\SyliusBonusPointsPlugin\Entity\AdjustmentInterface;
use BitBag\SyliusBonusPointsPlugin\Entity\BonusPointsAwareInterface;
use BitBag\SyliusBonusPointsPlugin\Entity\BonusPointsInterface;
use BitBag\SyliusBonusPointsPlugin\Purifier\OrderBonusPointsPurifierInterface;
use Doctrine\Persistence\ObjectManager;
use Sylius\Component\Order\Factory\AdjustmentFactoryInterface;
use Sylius\Component\Order\Model\OrderInterface;
use Sylius\Component\Order\Processor\OrderProcessorInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Webmozart\Assert\Assert;

final class OrderBonusPointsProcessor implements OrderProcessorInterface
{
    public function __construct(
        private readonly RepositoryInterface $bonusPointsRepository,
        private readonly ObjectManager $bonusPointsManager,
        private readonly AdjustmentFactoryInterface $adjustmentFactory,
        private readonly OrderBonusPointsPurifierInterface $orderBonusPointsPurifier,
    ) {
    }

    public function process(OrderInterface $order): void
    {
        Assert::isInstanceOf($order, BonusPointsAwareInterface::class);

        /** @var BonusPointsInterface[] $bonusPoints */
        $bonusPoints = $this->bonusPointsRepository->findBy([
            'order' => $order,
            'isUsed' => true,
        ]);

        if (0 === count($bonusPoints)) {
            return;
        }

        $order->removeAdjustments(AdjustmentInterface::ORDER_BONUS_POINTS_ADJUSTMENT);

        $totalUsedPoints = 0;

        foreach ($bonusPoints as $bonusPoint) {
            $parentBonusPoint = $bonusPoint->getOriginalBonusPoints();

            if (null === $parentBonusPoint) {
                continue;
            }

            if (0 === $bonusPoint->getPoints() || $parentBonusPoint->isExpired()) {
                $this->orderBonusPointsPurifier->purify($bonusPoint);

                continue;
            }

            $totalUsedPoints += $bonusPoint->getPoints();
        }

        if (0 >= $totalUsedPoints) {
            return;
        }

        if ($order->getItemsTotal() < $totalUsedPoints) {
            $pennies = $order->getItemsTotal() % 100;
            $decreasePoints = ($totalUsedPoints - ($order->getItemsTotal() - $pennies));
            $totalUsedPoints -= $decreasePoints;

            $this->decreaseBonusPoints($bonusPoints, $decreasePoints);
        }

        $adjustment = $this->adjustmentFactory->createWithData(
            AdjustmentInterface::ORDER_BONUS_POINTS_ADJUSTMENT,
            AdjustmentInterface::ORDER_BONUS_POINTS_ADJUSTMENT,
            (-1 * $totalUsedPoints),
        );

        $adjustment->setOriginCode(AdjustmentInterface::ORDER_BONUS_POINTS_ADJUSTMENT);

        $adjustment->setAdjustable($order);

        $order->addAdjustment($adjustment);
    }

    /** @param BonusPointsInterface[] $bonusPoints */
    private function decreaseBonusPoints(array $bonusPoints, int $decreasePoints): void
    {
        foreach ($bonusPoints as $bonusPoint) {
            if (0 >= $decreasePoints) {
                break;
            }

            $points = (int) $bonusPoint->getPoints();

            if (0 >= $points) {
                continue;
            }

            if ($points > $decreasePoints) {
                $bonusPoint->setPoints($points - $decreasePoints);

                $this->bonusPointsManager->persist($bonusPoint);

                break;
            }

            $decreasePoints -= $points;

            $this->bonusPointsManager->remove($bonusPoint);
        }
    }
}
