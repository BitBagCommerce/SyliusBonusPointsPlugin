<?php

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\OrderProcessing;

use BitBag\SyliusBonusPointsPlugin\Entity\AdjustmentInterface;
use BitBag\SyliusBonusPointsPlugin\Entity\BonusPointsAwareInterface;
use BitBag\SyliusBonusPointsPlugin\Entity\BonusPointsInterface;
use BitBag\SyliusBonusPointsPlugin\Purifier\OrderBonusPointsPurifierInterface;
use Sylius\Component\Order\Factory\AdjustmentFactoryInterface;
use Sylius\Component\Order\Model\OrderInterface;
use Sylius\Component\Order\Processor\OrderProcessorInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Webmozart\Assert\Assert;

final class OrderBonusPointsProcessor implements OrderProcessorInterface
{
    /** @var RepositoryInterface */
    private $bonusPointsRepository;

    /** @var AdjustmentFactoryInterface */
    private $adjustmentFactory;

    /** @var OrderBonusPointsPurifierInterface */
    private $orderBonusPointsPurifier;

    public function __construct(
        RepositoryInterface $bonusPointsRepository,
        AdjustmentFactoryInterface $adjustmentFactory,
        OrderBonusPointsPurifierInterface $orderBonusPointsPurifier
    ) {
        $this->bonusPointsRepository = $bonusPointsRepository;
        $this->adjustmentFactory = $adjustmentFactory;
        $this->orderBonusPointsPurifier = $orderBonusPointsPurifier;
    }

    public function process(OrderInterface $order): void
    {
        Assert::isInstanceOf($order, BonusPointsAwareInterface::class);

        /** @var BonusPointsInterface|null $bonusPoints */
        $bonusPoints = $this->bonusPointsRepository->findOneBy([
            'order' => $order,
            'isUsed' => true,
        ]);

        if (null === $bonusPoints) {
            return;
        }

        if (0 === $bonusPoints->getPoints()) {
            $this->orderBonusPointsPurifier->purify($bonusPoints);
            $this->bonusPointsRepository->add($bonusPoints);

            return;
        }

        $order->removeAdjustments(AdjustmentInterface::ORDER_BONUS_POINTS_ADJUSTMENT);

        $adjustment = $this->adjustmentFactory->createWithData(
            AdjustmentInterface::ORDER_BONUS_POINTS_ADJUSTMENT,
            AdjustmentInterface::ORDER_BONUS_POINTS_ADJUSTMENT,
            (-1 * (int) $bonusPoints->getPoints())
        );

        $adjustment->setOriginCode(AdjustmentInterface::ORDER_BONUS_POINTS_ADJUSTMENT);

        $adjustment->setAdjustable($order);

        $order->addAdjustment($adjustment);
    }
}
