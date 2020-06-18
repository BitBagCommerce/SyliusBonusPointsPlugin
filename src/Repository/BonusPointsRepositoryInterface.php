<?php

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Repository;

use Sylius\Component\Core\Model\Customer;
use Sylius\Component\Resource\Repository\RepositoryInterface;

interface BonusPointsRepositoryInterface extends RepositoryInterface
{
    public function findAllCustomerPointsMovements(Customer $customer): array;
}
