<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Sylius\Component\Customer\Model\CustomerInterface;

class CustomerBonusPoints implements CustomerBonusPointsInterface
{
    /** @var int */
    protected $id;

    /** @var CustomerInterface|null */
    protected $customer;

    /** @var Collection<int,BonusPointsInterface>|BonusPointsInterface[] */
    protected $bonusPoints;

    /** @var Collection<int,BonusPointsInterface> */
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

    /**
     * @return Collection<int,BonusPointsInterface>
     */
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

    public function getSortedNotUsedAndNotExpired(?\DateTime $dateTime = null): Collection
    {
        $dateTime = null === $dateTime ? new \DateTime() : $dateTime;

        $bonusPoints = $this->bonusPoints->filter(function (BonusPointsInterface $bonusPoints) use ($dateTime): bool {
            return false === $bonusPoints->isUsed() && !$bonusPoints->isExpired($dateTime);
        });

        $orderBy = (Criteria::create())->orderBy([
            'expiresAt' => Criteria::ASC,
        ]);

        return $bonusPoints->matching($orderBy);
    }
}
