<?php

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Entity;

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
}
