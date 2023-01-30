<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusBonusPointsPlugin\OrderProcessing;

use BitBag\SyliusBonusPointsPlugin\Entity\AdjustmentInterface;
use BitBag\SyliusBonusPointsPlugin\Entity\BonusPointsInterface;
use BitBag\SyliusBonusPointsPlugin\OrderProcessing\OrderBonusPointsProcessor;
use BitBag\SyliusBonusPointsPlugin\Purifier\OrderBonusPointsPurifierInterface;
use Doctrine\Common\Collections\ArrayCollection;
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
        $bonusPoints->getPoints()->willReturn(1234);

        $bonusPointsCollection = new ArrayCollection([$bonusPoints->getWrappedObject()]);
        $bonusPointsRepository->findBy(['order' => $order, 'isUsed' => true])->willReturn($bonusPointsCollection);

        $adjustmentFactory->createWithData(
            AdjustmentInterface::ORDER_BONUS_POINTS_ADJUSTMENT,
            AdjustmentInterface::ORDER_BONUS_POINTS_ADJUSTMENT,
            -1234
        )->willReturn($adjustment);

        $order->removeAdjustments(AdjustmentInterface::ORDER_BONUS_POINTS_ADJUSTMENT)->shouldBeCalled();
        $adjustment->setOriginCode(AdjustmentInterface::ORDER_BONUS_POINTS_ADJUSTMENT)->shouldBeCalled();
        $adjustment->setAdjustable($order)->shouldBeCalled();
        $order->addAdjustment($adjustment)->shouldBeCalled();
        $order->getItemsTotal()->willReturn(5420);

        $this->process($order);
    }

    function it_processes_when_bonus_points_have_zero_value(
        Order $order,
        RepositoryInterface $bonusPointsRepository,
        BonusPointsInterface $bonusPoints,
        OrderBonusPointsPurifierInterface $orderBonusPointsPurifier
    ): void {
        $bonusPointsCollection = new ArrayCollection([$bonusPoints->getWrappedObject()]);

        $bonusPointsRepository->findBy(['order' => $order, 'isUsed' => true])->willReturn($bonusPointsCollection);
        $bonusPoints->getPoints()->willReturn(0);

        $bonusPointsRepository->findBy(['order' => $order, 'isUsed' => true])->shouldBeCalled();
        $bonusPoints->getPoints()->shouldBeCalled();
        $orderBonusPointsPurifier->purify($bonusPoints)->shouldBeCalled();
        $bonusPointsRepository->add($bonusPoints)->shouldBeCalled();

        $this->process($order);
    }
}
