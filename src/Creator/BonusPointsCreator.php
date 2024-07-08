<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Creator;

use BitBag\SyliusBonusPointsPlugin\Entity\BonusPointsInterface;
use BitBag\SyliusBonusPointsPlugin\Entity\CustomerBonusPointsInterface;
use BitBag\SyliusBonusPointsPlugin\Repository\BonusPointsRepositoryInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

final class BonusPointsCreator implements BonusPointsCreatorInterface
{
    public function __construct(
        private FactoryInterface $bonusPointsFactory,
        private BonusPointsRepositoryInterface $bonusPointsRepository,
    ) {
    }

    public function createWithData(
        CustomerBonusPointsInterface $customerBonusPoints,
        OrderInterface $order,
        int $points,
        BonusPointsInterface $parentBonusPoints = null,
    ): BonusPointsInterface {
        /** @var BonusPointsInterface $bonusPoints */
        $bonusPoints = $this->bonusPointsFactory->createNew();

        $bonusPoints->setIsUsed(true);
        $bonusPoints->setPoints($points);
        $bonusPoints->setOrder($order);

        $customerBonusPoints->addBonusPointsUsed($bonusPoints);

        if (null !== $parentBonusPoints) {
            $parentBonusPoints->addRelatedBonusPoints($bonusPoints);
        }

        $this->bonusPointsRepository->add($bonusPoints);

        return $bonusPoints;
    }
}
