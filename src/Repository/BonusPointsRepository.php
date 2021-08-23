<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Repository;

use BitBag\SyliusBonusPointsPlugin\Entity\CustomerBonusPoints;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Customer\Model\CustomerInterface;

class BonusPointsRepository extends EntityRepository implements BonusPointsRepositoryInterface
{
    public function findAllCustomerPointsMovements(CustomerInterface $customer): array
    {
        return $this->createQueryBuilder('bp')
            ->leftJoin(CustomerBonusPoints::class, 'cbp', 'WITH', 'cbp.customer = :customer')
            ->leftJoin('cbp.bonusPoints', 'bonusPointsNotUsed')
            ->leftJoin('cbp.bonusPointsUsed', 'bonusPointsUsed')
            ->andWhere('bonusPointsUsed.id = bp.id OR bonusPointsNotUsed.id = bp.id')
            ->setParameter('customer', $customer)
            ->addOrderBy('bp.updatedAt', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
