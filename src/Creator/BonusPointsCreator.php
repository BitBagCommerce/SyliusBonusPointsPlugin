<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
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
    /** @var FactoryInterface */
    private $bonusPointsFactory;

    /** @var BonusPointsRepositoryInterface */
    private $bonusPointsRepository;

    public function __construct(
        FactoryInterface $bonusPointsFactory,
        BonusPointsRepositoryInterface $bonusPointsRepository,
    ) {
        $this->bonusPointsFactory = $bonusPointsFactory;
        $this->bonusPointsRepository = $bonusPointsRepository;
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
