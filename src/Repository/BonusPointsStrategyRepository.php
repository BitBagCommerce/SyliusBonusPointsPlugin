<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Repository;

use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

class BonusPointsStrategyRepository extends EntityRepository implements BonusPointsStrategyRepositoryInterface
{
    public function findAllActive(): array
    {
        return $this->createQueryBuilder('o')
            ->where('o.enabled = true')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findActiveByCalculatorType(string $calculatorType): array
    {
        return $this->createQueryBuilder('o')
            ->where('o.enabled = true')
            ->andWhere('o.calculatorType = :calculatorType')
            ->setParameter('calculatorType', $calculatorType)
            ->getQuery()
            ->getResult()
        ;
    }
}
