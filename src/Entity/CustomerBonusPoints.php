<?php

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Core\Model\CustomerInterface;

class CustomerBonusPoints implements CustomerBonusPointsInterface
{
    /** @var int */
    protected $id;

    /** @var CustomerInterface */
    protected $customer;

    /** @var BonusPointsInterface|Collection */
    protected $bonusPoints;

    /** @var BonusPointsInterface|Collection */
    protected $bonusPointsUsed;

    public function __construct()
    {
        $this->bonusPoints = new ArrayCollection();
        $this->bonusPointsUsed = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCustomer(): ?CustomerInterface
    {
        return $this->customer;
    }

    public function setCustomer(?CustomerInterface $customer): void
    {
        $this->customer = $customer;
    }

    public function getBonusPoints(): Collection
    {
        return $this->bonusPoints;
    }

    public function addBonusPoints(BonusPointsInterface $bonusPoints): void
    {
        if (!$this->hasBonusPoints($bonusPoints)) {
            $this->bonusPoints->add($bonusPoints);
        }
    }

    public function removeBonusPoints(BonusPointsInterface $bonusPoints): void
    {
        if ($this->hasBonusPoints($bonusPoints)) {
            $this->bonusPoints->removeElement($bonusPoints);
        }
    }

    public function hasBonusPoints(BonusPointsInterface $bonusPoints): bool
    {
        return $this->bonusPoints->contains($bonusPoints);
    }

    public function getBonusPointsUsed(): Collection
    {
        return $this->bonusPointsUsed;
    }

    public function addBonusPointsUsed(BonusPointsInterface $bonusPointsUsed): void
    {
        if (!$this->hasBonusPointsUsed($bonusPointsUsed)) {
            $this->bonusPointsUsed->add($bonusPointsUsed);
        }
    }

    public function removeBonusPointsUsed(BonusPointsInterface $bonusPointsUsed): void
    {
        if ($this->hasBonusPoints($bonusPointsUsed)) {
            $this->bonusPointsUsed->removeElement($bonusPointsUsed);
        }
    }

    public function hasBonusPointsUsed(BonusPointsInterface $bonusPointsUsed): bool
    {
        return $this->bonusPointsUsed->contains($bonusPointsUsed);
    }
}
