<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Resource\Model\TimestampableTrait;

class BonusPoints implements BonusPointsInterface
{
    use TimestampableTrait;

    /** @var int */
    protected $id;

    /** @var OrderInterface|null */
    protected $order;

    /** @var int|null */
    protected $points;

    /** @var bool */
    protected $isUsed = false;

    /** @var \DateTimeInterface|null */
    protected $expiresAt;

    /** @var self|null */
    protected $originalBonusPoints;

    /** @var Collection */
    protected $relatedBonusPoints;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->relatedBonusPoints = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrder(): ?OrderInterface
    {
        return $this->order;
    }

    public function setOrder(?OrderInterface $order): void
    {
        $this->order = $order;
    }

    public function getPoints(): ?int
    {
        return $this->points;
    }

    public function setPoints(?int $points): void
    {
        $this->points = $points;
    }

    public function isUsed(): bool
    {
        return $this->isUsed;
    }

    public function setIsUsed(bool $isUsed): void
    {
        $this->isUsed = $isUsed;
    }

    public function getExpiresAt(): ?\DateTimeInterface
    {
        return $this->expiresAt;
    }

    public function setExpiresAt(?\DateTimeInterface $expiresAt): void
    {
        $this->expiresAt = $expiresAt;
    }

    public function setOriginalBonusPoints(?BonusPointsInterface $bonusPoints): void
    {
        $this->originalBonusPoints = $bonusPoints;
    }

    public function getOriginalBonusPoints(): ?BonusPointsInterface
    {
        return $this->originalBonusPoints;
    }

    public function addRelatedBonusPoints(BonusPointsInterface $bonusPoints): void
    {
        if (false === $this->hasRelatedBonusPoints($bonusPoints)) {
            $bonusPoints->setOriginalBonusPoints($this);

            $this->relatedBonusPoints->add($bonusPoints);
        }
    }

    public function removeRelatedBonusPoints(BonusPointsInterface $bonusPoints): void
    {
        if (true === $this->relatedBonusPoints->contains($bonusPoints)) {
            $this->relatedBonusPoints->removeElement($bonusPoints);
        }
    }

    public function hasRelatedBonusPoints(BonusPointsInterface $bonusPoints): bool
    {
        return $this->relatedBonusPoints->contains($bonusPoints);
    }

    public function getRelatedBonusPoints(): Collection
    {
        return $this->relatedBonusPoints;
    }

    public function isExpired(?\DateTime $dateTime = null): bool
    {
        if (null === $dateTime) {
            $dateTime = new \DateTime();
        }

        return $dateTime > $this->expiresAt;
    }

    public function getLeftPointsFromAvailablePool(): int
    {
        $relatedBonusPoints = $this->getRelatedBonusPoints();

        if ($relatedBonusPoints->isEmpty()) {
            return $this->getPoints();
        }

        $totalUsedPointsFromPool = 0;

        /** @var BonusPointsInterface $bonusPoint */
        foreach ($relatedBonusPoints as $bonusPoint) {
            if (true === $bonusPoint->isUsed()) {
                $totalUsedPointsFromPool += $bonusPoint->getPoints();
            }
        }

        $result = $this->getPoints() - $totalUsedPointsFromPool;

        return 0 > $result ? 0 : $result;
    }
}
