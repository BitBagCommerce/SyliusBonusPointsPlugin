<?php

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Repository;

use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Core\Model\Customer;

class BonusPointsRepository extends EntityRepository implements BonusPointsRepositoryInterface
{
    public function findAllCustomerPointsMovements(Customer $customer): array
    {
        return $this->createQueryBuilder('bp')
            ->leftJoin('App\Entity\BonusPoints\CustomerBonusPoints', 'cbp', 'WITH', 'cbp.customer = :customer')
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
