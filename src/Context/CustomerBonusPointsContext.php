<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
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
