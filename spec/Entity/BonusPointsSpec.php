<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusBonusPointsPlugin\Entity;

use BitBag\SyliusBonusPointsPlugin\Entity\BonusPoints;
use BitBag\SyliusBonusPointsPlugin\Entity\BonusPointsInterface;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Core\Model\OrderInterface;

final class BonusPointsSpec extends ObjectBehavior
{
    function it_is_initializable(): void
    {
        $this->shouldHaveType(BonusPoints::class);
    }

    function it_implements_blacklisting_rule_interface(): void
    {
        $this->shouldHaveType(BonusPointsInterface::class);
    }

    function it_has_null_id_by_default(): void
    {
        $this->getId()->shouldReturn(null);
    }

    function it_has_no_points_by_default(): void
    {
        $this->getPoints()->shouldReturn(null);
    }

    function it_is_not_used_by_default(): void
    {
        $this->isUsed()->shouldReturn(false);
    }

    function it_sets_order(OrderInterface $order): void
    {
        $this->setOrder($order);

        $this->getOrder()->shouldReturn($order);
    }

    function it_sets_points(): void
    {
        $this->setPoints(1231);

        $this->getPoints()->shouldReturn(1231);
    }

    function it_switches_is_used_flag(): void
    {
        $this->setIsUsed(true);

        $this->isUsed()->shouldReturn(true);
    }

    function it_calculates_left_points_from_available_pool(BonusPoints $relatedBonusPoints): void
    {
        $this->setPoints(256445);
        $this->setIsUsed(false);
        $relatedBonusPoints->setOriginalBonusPoints($this)->shouldBeCalled();
        $relatedBonusPoints->getPoints()->willReturn(45647);
        $relatedBonusPoints->isUsed()->willReturn(true);
        $this->addRelatedBonusPoints($relatedBonusPoints);
        $this->getLeftPointsFromAvailablePool()->shouldReturn(210798);
    }

    function it_calculates_left_points_from_available_pool_when_no_related_bonus_points(): void
    {
        $this->setPoints(100);
        $this->getLeftPointsFromAvailablePool()->shouldReturn(100);
    }

    function it_checks_if_it_is_expired(): void
    {
        $this->setExpiresAt(new \DateTime('+1 day'));
        $this->isExpired()->shouldReturn(false);

        $this->setExpiresAt(new \DateTime('-1 day'));
        $this->isExpired()->shouldReturn(true);
    }
}
