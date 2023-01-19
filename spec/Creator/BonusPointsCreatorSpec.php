<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

namespace spec\BitBag\SyliusBonusPointsPlugin\Creator;

use BitBag\SyliusBonusPointsPlugin\Entity\BonusPointsInterface;
use BitBag\SyliusBonusPointsPlugin\Entity\CustomerBonusPointsInterface;
use BitBag\SyliusBonusPointsPlugin\Creator\BonusPointsCreator;
use BitBag\SyliusBonusPointsPlugin\Repository\BonusPointsRepositoryInterface;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use BitBag\SyliusBonusPointsPlugin\Creator\BonusPointsCreatorInterface;

final class BonusPointsCreatorSpec extends ObjectBehavior
{
    function let(FactoryInterface $bonusPointsFactory, BonusPointsRepositoryInterface $bonusPointsRepository)
    {
        $this->beConstructedWith($bonusPointsFactory, $bonusPointsRepository);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(BonusPointsCreator::class);
    }

    function it_implements_bonus_points_creator_interface()
    {
        $this->shouldImplement(BonusPointsCreatorInterface::class);
    }

    function it_creates_bonus_points_with_data(
        FactoryInterface $bonusPointsFactory,
        BonusPointsRepositoryInterface $bonusPointsRepository,
        BonusPointsInterface $bonusPoints,
        CustomerBonusPointsInterface $customerBonusPoints,
        OrderInterface $order
    ) {
        $bonusPointsFactory->createNew()->willReturn($bonusPoints);

        $bonusPoints->setIsUsed(true)->shouldBeCalled();
        $bonusPoints->setPoints(47)->shouldBeCalled();
        $bonusPoints->setOrder($order)->shouldBeCalled();

        $customerBonusPoints->addBonusPointsUsed($bonusPoints)->shouldBeCalled();

        $bonusPointsRepository->add($bonusPoints)->shouldBeCalled();

        $this->createWithData($customerBonusPoints, $order, 47)->shouldReturn($bonusPoints);
    }

    function it_creates_bonus_points_with_parent_bonus_points(
        FactoryInterface $bonusPointsFactory,
        BonusPointsRepositoryInterface $bonusPointsRepository,
        BonusPointsInterface $bonusPoints,
        BonusPointsInterface $parentBonusPoints,
        CustomerBonusPointsInterface $customerBonusPoints,
        OrderInterface $order
    ) {
        $bonusPointsFactory->createNew()->willReturn($bonusPoints);

        $bonusPoints->setIsUsed(true)->shouldBeCalled();
        $bonusPoints->setPoints(69)->shouldBeCalled();
        $bonusPoints->setOrder($order)->shouldBeCalled();

        $customerBonusPoints->addBonusPointsUsed($bonusPoints)->shouldBeCalled();

        $parentBonusPoints->addRelatedBonusPoints($bonusPoints)->shouldBeCalled();

        $bonusPointsRepository->add($bonusPoints)->shouldBeCalled();

        $this->createWithData($customerBonusPoints, $order, 69, $parentBonusPoints)->shouldReturn($bonusPoints);
    }
}
