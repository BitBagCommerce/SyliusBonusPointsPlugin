<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Entity;

use Doctrine\Common\Collections\Collection;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

interface CustomerBonusPointsInterface extends ResourceInterface
{
    public function getCustomer(): ?CustomerInterface;

    public function setCustomer(?CustomerInterface $customer): void;

    /**
     * @return Collection<int,BonusPointsInterface>
     */
    public function getBonusPoints(): Collection;

    public function addBonusPoints(BonusPointsInterface $bonusPoints): void;

    public function removeBonusPoints(BonusPointsInterface $bonusPoints): void;

    public function hasBonusPoints(BonusPointsInterface $bonusPoints): bool;

    /**
     * @return Collection<int,BonusPointsInterface>
     */
    public function getBonusPointsUsed(): Collection;

    public function addBonusPointsUsed(BonusPointsInterface $bonusPointsUsed): void;

    public function removeBonusPointsUsed(BonusPointsInterface $bonusPointsUsed): void;

    public function hasBonusPointsUsed(BonusPointsInterface $bonusPointsUsed): bool;
}
