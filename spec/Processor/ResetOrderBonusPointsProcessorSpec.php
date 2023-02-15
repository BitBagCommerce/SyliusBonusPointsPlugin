<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusBonusPointsPlugin\Processor;

use BitBag\SyliusBonusPointsPlugin\Entity\BonusPointsInterface;
use BitBag\SyliusBonusPointsPlugin\Processor\ResetOrderBonusPointsProcessor;
use BitBag\SyliusBonusPointsPlugin\Processor\ResetOrderBonusPointsProcessorInterface;
use BitBag\SyliusBonusPointsPlugin\Purifier\OrderBonusPointsPurifierInterface;
use Doctrine\Common\Collections\ArrayCollection;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Tests\BitBag\SyliusBonusPointsPlugin\Entity\Order;

final class ResetOrderBonusPointsProcessorSpec extends ObjectBehavior
{
    function let(
        OrderBonusPointsPurifierInterface $orderBonusPointsPurifier,
        RepositoryInterface $bonusPointsRepository
    ): void {
        $this->beConstructedWith($orderBonusPointsPurifier, $bonusPointsRepository);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(ResetOrderBonusPointsProcessor::class);
    }

    function it_implements_interface(): void
    {
        $this->shouldImplement(ResetOrderBonusPointsProcessorInterface::class);
    }

    function it_processes(
        Order $order,
        RepositoryInterface $bonusPointsRepository,
        BonusPointsInterface $bonusPoints,
        OrderBonusPointsPurifierInterface $orderBonusPointsPurifier
    ): void {
        $bonusPointsCollection = new ArrayCollection([$bonusPoints->getWrappedObject()]);
        $bonusPointsRepository->findBy(['order' => $order, 'isUsed' => true])->willReturn($bonusPointsCollection);

        $bonusPointsRepository->findBy(['order' => $order, 'isUsed' => true])->shouldBeCalled();
        $orderBonusPointsPurifier->purify($bonusPoints)->shouldBeCalled();

        $this->process($order);
    }

    function it_does_not_process_if_it_does_not_find_bonus_points(
        Order $order,
        RepositoryInterface $bonusPointsRepository,
        BonusPointsInterface $bonusPoints,
        OrderBonusPointsPurifierInterface $orderBonusPointsPurifier
    ): void {
        $bonusPointsRepository->findBy(['order' => $order, 'isUsed' => true])->willReturn([]);

        $bonusPointsRepository->findBy(['order' => $order, 'isUsed' => true])->shouldBeCalled();
        $orderBonusPointsPurifier->purify($bonusPoints)->shouldNotBeCalled();
        $bonusPointsRepository->add($bonusPoints)->shouldNotBeCalled();

        $this->process($order);
    }
}
