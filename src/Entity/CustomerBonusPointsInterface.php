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
use Sylius\Component\Customer\Model\CustomerInterface;
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

    /**
     * @return Collection<int,BonusPointsInterface>
     */
    public function getSortedNotUsedAndNotExpired(?\DateTime $dateTime = null): Collection;
}
