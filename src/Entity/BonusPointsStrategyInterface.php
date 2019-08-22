<?php

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Entity;

use Doctrine\Common\Collections\Collection;
use Sylius\Component\Resource\Model\CodeAwareInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TimestampableInterface;
use Sylius\Component\Resource\Model\ToggleableInterface;

interface BonusPointsStrategyInterface extends
    ResourceInterface,
    TimestampableInterface,
    ToggleableInterface,
    CodeAwareInterface
{
    public function getCode(): ?string;

    public function setCode(?string $code): void;

    public function getName(): ?string;

    public function setName(?string $name): void;

    public function getCalculatorConfiguration(): array;

    public function setCalculatorConfiguration(array $calculatorConfiguration): void;

    public function getRules(): Collection;

    public function hasRules(): bool;

    public function hasRule(BonusPointsStrategyRuleInterface $rule): bool;

    public function addRule(BonusPointsStrategyRuleInterface $rule): void;

    public function removeRule(BonusPointsStrategyRuleInterface $rule): void;

    public function getCalculatorType(): ?string;

    public function setCalculatorType(?string $calculatorType): void;

    public function isDeductBonusPoints(): bool;

    public function setIsDeductBonusPoints(bool $isDeductBonusPoints): void;
}
