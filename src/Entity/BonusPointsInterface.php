<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Entity;

use Doctrine\Common\Collections\Collection;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TimestampableInterface;

interface BonusPointsInterface extends ResourceInterface, TimestampableInterface
{
    public function getOrder(): ?OrderInterface;

    public function setOrder(?OrderInterface $order): void;

    public function getPoints(): ?int;

    public function setPoints(?int $points): void;

    public function isUsed(): bool;

    public function setIsUsed(bool $isUsed): void;

    public function getExpiresAt(): ?\DateTimeInterface;

    public function setExpiresAt(?\DateTimeInterface $expiresAt): void;

    public function setOriginalBonusPoints(?self $bonusPoints): void;

    public function getOriginalBonusPoints(): ?self;

    public function addRelatedBonusPoints(self $bonusPoints): void;

    public function removeRelatedBonusPoints(self $bonusPoints): void;

    public function hasRelatedBonusPoints(self $bonusPoints): bool;

    /**
     * @return Collection<int,self>
     */
    public function getRelatedBonusPoints(): Collection;

    public function isExpired(?\DateTime $dateTime = null): bool;

    public function getLeftPointsFromAvailablePool(?OrderInterface $withoutOrder = null): int;
}
