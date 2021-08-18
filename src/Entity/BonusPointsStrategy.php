<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Resource\Model\TimestampableTrait;
use Sylius\Component\Resource\Model\ToggleableTrait;

class BonusPointsStrategy implements BonusPointsStrategyInterface
{
    use ToggleableTrait;
    use TimestampableTrait;

    /** @var int */
    protected $id;

    /** @var string|null */
    protected $code;

    /** @var string|null */
    protected $name;

    /** @var string|null */
    protected $calculatorType;

    /** @var array */
    protected $calculatorConfiguration = [];

    /** @var Collection<int,BonusPointsStrategyRuleInterface>|BonusPointsStrategyRuleInterface[] */
    protected $rules;

    /** @var bool */
    protected $isDeductBonusPoints = false;

    public function __construct()
    {
        $this->createdAt = new DateTime();
        $this->rules = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getCalculatorType(): ?string
    {
        return $this->calculatorType;
    }

    public function setCalculatorType(?string $calculatorType): void
    {
        $this->calculatorType = $calculatorType;
    }

    public function getCalculatorConfiguration(): array
    {
        return $this->calculatorConfiguration;
    }

    public function setCalculatorConfiguration(array $calculatorConfiguration): void
    {
        $this->calculatorConfiguration = $calculatorConfiguration;
    }

    /**
     * @return Collection<int,BonusPointsStrategyRuleInterface>
     */
    public function getRules(): Collection
    {
        return $this->rules;
    }

    public function hasRules(): bool
    {
        return !$this->rules->isEmpty();
    }

    public function hasRule(BonusPointsStrategyRuleInterface $rule): bool
    {
        return $this->rules->contains($rule);
    }

    public function addRule(BonusPointsStrategyRuleInterface $rule): void
    {
        if (!$this->hasRule($rule)) {
            $rule->setBonusPointsStrategy($this);
            $this->rules->add($rule);
        }
    }

    public function removeRule(BonusPointsStrategyRuleInterface $rule): void
    {
        $rule->setBonusPointsStrategy(null);
        $this->rules->removeElement($rule);
    }

    public function isDeductBonusPoints(): bool
    {
        return $this->isDeductBonusPoints;
    }

    public function setIsDeductBonusPoints(bool $isDeductBonusPoints): void
    {
        $this->isDeductBonusPoints = $isDeductBonusPoints;
    }
}
