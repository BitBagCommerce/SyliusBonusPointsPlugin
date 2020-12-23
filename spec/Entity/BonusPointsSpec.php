<?php

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
}
