<?php

declare(strict_types=1);

namespace spec\BitBag\SyliusBonusPointsPlugin\Entity;

use BitBag\SyliusBonusPointsPlugin\Entity\BonusPointsStrategy;
use BitBag\SyliusBonusPointsPlugin\Entity\BonusPointsStrategyInterface;
use BitBag\SyliusBonusPointsPlugin\Entity\BonusPointsStrategyRuleInterface;
use PhpSpec\ObjectBehavior;

final class BonusPointsStrategySpec extends ObjectBehavior
{
    function it_is_initializable(): void
    {
        $this->shouldHaveType(BonusPointsStrategy::class);
    }

    function it_implements_blacklisting_rule_interface(): void
    {
        $this->shouldHaveType(BonusPointsStrategyInterface::class);
    }

    function it_has_null_id_by_default(): void
    {
        $this->getId()->shouldReturn(null);
    }

    function it_has_no_code_by_default(): void
    {
        $this->getCode()->shouldReturn(null);
    }

    function it_has_no_name_by_default(): void
    {
        $this->getName()->shouldReturn(null);
    }

    function it_has_no_calculator_type_by_default(): void
    {
        $this->getCalculatorType()->shouldReturn(null);
    }

    function it_has_empty_calculator_configuration_by_default(): void
    {
        $this->getCalculatorConfiguration()->shouldReturn([]);
    }

    function it_has_empty_rules_by_default(): void
    {
        $this->getRules()->isEmpty()->shouldReturn(true);
    }

    function it_sets_code(): void
    {
        $this->setCode('test_code');

        $this->getCode()->shouldReturn('test_code');
    }

    function it_sets_name(): void
    {
        $this->setName('Test name');

        $this->getName()->shouldReturn('Test name');
    }

    function it_sets_calculator_type(): void
    {
        $this->setCalculatorType('per_order_price');

        $this->getCalculatorType()->shouldReturn('per_order_price');
    }

    function it_switches_is_deduct_bonus_points_flag(): void
    {
        $this->setIsDeductBonusPoints(true);

        $this->isDeductBonusPoints()->shouldReturn(true);
    }

    function it_add_rules(BonusPointsStrategyRuleInterface $rule): void
    {
        $this->hasRule($rule)->shouldReturn(false);

        $this->addRule($rule);

        $this->hasRule($rule)->shouldReturn(true);
    }
}
