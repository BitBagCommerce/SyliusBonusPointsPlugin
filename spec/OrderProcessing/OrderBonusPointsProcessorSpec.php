<?php

declare(strict_types=1);

namespace spec\BitBag\SyliusBonusPointsPlugin\OrderProcessing;

use BitBag\SyliusBonusPointsPlugin\Entity\AdjustmentInterface;
use BitBag\SyliusBonusPointsPlugin\Entity\BonusPointsInterface;
use BitBag\SyliusBonusPointsPlugin\OrderProcessing\OrderBonusPointsProcessor;
use BitBag\SyliusBonusPointsPlugin\Purifier\OrderBonusPointsPurifierInterface;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Order\Factory\AdjustmentFactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Tests\BitBag\SyliusBonusPointsPlugin\Entity\Order;

final class OrderBonusPointsProcessorSpec extends ObjectBehavior
{
    function let(
        RepositoryInterface $bonusPointsRepository,
        AdjustmentFactoryInterface $adjustmentFactory,
        OrderBonusPointsPurifierInterface $orderBonusPointsPurifier
    ): void {
        $this->beConstructedWith($bonusPointsRepository, $adjustmentFactory, $orderBonusPointsPurifier);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(OrderBonusPointsProcessor::class);
    }

    function it_processes(
        Order $order,
        RepositoryInterface $bonusPointsRepository,
        AdjustmentFactoryInterface $adjustmentFactory,
        BonusPointsInterface $bonusPoints,
        AdjustmentInterface $adjustment
    ): void {
        $bonusPointsRepository->findOneBy(['order' => $order, 'isUsed' => true])->willReturn($bonusPoints);
        $bonusPoints->getPoints()->willReturn(1234);
        $adjustmentFactory->createWithData(
            AdjustmentInterface::ORDER_BONUS_POINTS_ADJUSTMENT,
            AdjustmentInterface::ORDER_BONUS_POINTS_ADJUSTMENT,
        -1234
        )->willReturn($adjustment);

        $order->removeAdjustments(AdjustmentInterface::ORDER_BONUS_POINTS_ADJUSTMENT)->shouldBeCalled();
        $adjustment->setOriginCode(AdjustmentInterface::ORDER_BONUS_POINTS_ADJUSTMENT)->shouldBeCalled();
        $adjustment->setAdjustable($order)->shouldBeCalled();
        $order->addAdjustment($adjustment)->shouldBeCalled();

        $this->process($order);
    }

    function it_processes_when_bonus_points_have_zero_value(
        Order $order,
        RepositoryInterface $bonusPointsRepository,
        BonusPointsInterface $bonusPoints,
        OrderBonusPointsPurifierInterface $orderBonusPointsPurifier
    ): void {
        $bonusPointsRepository->findOneBy(['order' => $order, 'isUsed' => true])->willReturn($bonusPoints);
        $bonusPoints->getPoints()->willReturn(0);

        $bonusPointsRepository->findOneBy(['order' => $order, 'isUsed' => true])->shouldBeCalled();
        $bonusPoints->getPoints()->shouldBeCalled();
        $orderBonusPointsPurifier->purify($bonusPoints)->shouldBeCalled();
        $bonusPointsRepository->add($bonusPoints)->shouldBeCalled();

        $this->process($order);
    }
}
