<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Context;

use BitBag\SyliusBonusPointsPlugin\Entity\CustomerBonusPointsInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class CustomerBonusPointsContext implements CustomerBonusPointsContextInterface
{
    public function __construct(
        private CustomerContextInterface $customerContext,
        private RepositoryInterface $customerBonusPointsRepository,
        private FactoryInterface $customerBonusPointsFactory,
    ) {
    }

    public function getCustomerBonusPoints(): ?CustomerBonusPointsInterface
    {
        /** @var CustomerInterface|null $customer */
        $customer = $this->customerContext->getCustomer();

        if (null === $customer) {
            return null;
        }

        /** @var ?CustomerBonusPointsInterface $customerBonusPoints */
        $customerBonusPoints = $this->customerBonusPointsRepository->findOneBy([
            'customer' => $customer,
        ]);

        if (null === $customerBonusPoints) {
            /** @var CustomerBonusPointsInterface $customerBonusPoints */
            $customerBonusPoints = $this->customerBonusPointsFactory->createNew();

            $customerBonusPoints->setCustomer($customer);

            $this->customerBonusPointsRepository->add($customerBonusPoints);
        }

        return $customerBonusPoints;
    }
}
