<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Processor;

use BitBag\SyliusBonusPointsPlugin\Entity\AdjustmentInterface;
use BitBag\SyliusBonusPointsPlugin\Entity\BonusPointsInterface;
use BitBag\SyliusBonusPointsPlugin\Purifier\OrderBonusPointsPurifierInterface;
use Sylius\Component\Order\Model\OrderInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class ResetOrderBonusPointsProcessor implements ResetOrderBonusPointsProcessorInterface
{
    /** @var OrderBonusPointsPurifierInterface */
    private $orderBonusPointsPurifier;

    /** @var RepositoryInterface */
    private $bonusPointsRepository;

    public function __construct(
        OrderBonusPointsPurifierInterface $orderBonusPointsPurifier,
        RepositoryInterface $bonusPointsRepository,
    ) {
        $this->orderBonusPointsPurifier = $orderBonusPointsPurifier;
        $this->bonusPointsRepository = $bonusPointsRepository;
    }

    public function process(OrderInterface $order): void
    {
        /** @var BonusPointsInterface[] $bonusPoints */
        $bonusPoints = $this->bonusPointsRepository->findBy(['order' => $order, 'isUsed' => true]);

        $order->removeAdjustmentsRecursively(AdjustmentInterface::ORDER_BONUS_POINTS_ADJUSTMENT);

        foreach ($bonusPoints as $bonusPoint) {
            $this->orderBonusPointsPurifier->purify($bonusPoint);
        }
    }
}
