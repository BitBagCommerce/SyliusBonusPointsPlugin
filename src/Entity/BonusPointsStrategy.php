<?php

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Entity;

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

    /** @var string */
    protected $code;

    /** @var string */
    protected $name;

    /** @var int */
    protected $pointAmount;

    /** @var Collection|BonusPointsStrategyRuleInterface[] */
    protected $rules;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
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
}
