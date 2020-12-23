<?php

declare(strict_types=1);

namespace spec\BitBag\SyliusBonusPointsPlugin\Calculator;

use BitBag\SyliusBonusPointsPlugin\Calculator\BonusPointsStrategyCalculatorInterface;
use BitBag\SyliusBonusPointsPlugin\Calculator\PerOrderItemPercentageCalculator;
use BitBag\SyliusBonusPointsPlugin\Calculator\PerOrderPriceCalculator;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\OrderItemInterface;

final class PerOrderItemPercentageCalculatorSpec extends ObjectBehavior
{
    function it_is_initializable(): void
    {
        $this->shouldHaveType(PerOrderItemPercentageCalculator::class);
    }

    function it_implements_bonus_points_resolver_interface(): void
    {
        $this->shouldHaveType(BonusPointsStrategyCalculatorInterface::class);
    }

    function it_calculates(
        OrderItemInterface $orderItem,
        OrderInterface $order,
        ChannelInterface $channel
    ): void {
        $configuration = ['FASHION_WEB' => ['percentToCalculatePoints' => 0.1]];

        $orderItem->getOrder()->willReturn($order);
        $order->getChannel()->willReturn($channel);
        $channel->getCode()->willReturn('FASHION_WEB');
        $orderItem->getTotal()->willReturn(10000);

        $orderItem->getOrder()->shouldBeCalled();
        $order->getChannel()->shouldBeCalled();
        $channel->getCode()->shouldBeCalled();
        $orderItem->getTotal()->shouldBeCalled();

        $this->calculate($orderItem, $configuration, -1)->shouldReturn(1000);
    }
}
