<?php

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Entity;

use Doctrine\Common\Collections\Collection;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

interface CustomerBonusPointsInterface extends ResourceInterface
{
    public function getCustomer(): ?CustomerInterface;

    public function setCustomer(?CustomerInterface $customer): void;

    public function getBonusPoints(): Collection;

    public function addBonusPoints(BonusPointsInterface $bonusPoints): void;

    public function removeBonusPoints(BonusPointsInterface $bonusPoints): void;

    public function hasBonusPoints(BonusPointsInterface $bonusPoints): bool;

    public function getBonusPointsUsed(): Collection;

    public function addBonusPointsUsed(BonusPointsInterface $bonusPointsUsed): void;

    public function removeBonusPointsUsed(BonusPointsInterface $bonusPointsUsed): void;

    public function hasBonusPointsUsed(BonusPointsInterface $bonusPointsUsed): bool;
}
