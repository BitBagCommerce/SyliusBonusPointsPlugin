<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\EventListener;

use BitBag\SyliusBonusPointsPlugin\Context\CustomerBonusPointsContextInterface;
use BitBag\SyliusBonusPointsPlugin\Creator\BonusPointsCreatorInterface;
use BitBag\SyliusBonusPointsPlugin\Entity\BonusPointsAwareInterface;
use BitBag\SyliusBonusPointsPlugin\Entity\BonusPointsInterface;
use BitBag\SyliusBonusPointsPlugin\Entity\CustomerBonusPointsInterface;
use BitBag\SyliusBonusPointsPlugin\Processor\ResetOrderBonusPointsProcessorInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Webmozart\Assert\Assert;

final class OrderBonusPointsListener
{
    /** @var BonusPointsCreatorInterface */
    private $bonusPointsCreator;

    /** @var CustomerBonusPointsContextInterface */
    private $customerBonusPointsContext;

    /** @var ResetOrderBonusPointsProcessorInterface */
    private $resetBonusPointsProcessor;

    public function __construct(
        BonusPointsCreatorInterface $bonusPointsCreator,
        CustomerBonusPointsContextInterface $customerBonusPointsContext,
        ResetOrderBonusPointsProcessorInterface $resetBonusPointsProcessor
    ) {
        $this->bonusPointsCreator = $bonusPointsCreator;
        $this->customerBonusPointsContext = $customerBonusPointsContext;
        $this->resetBonusPointsProcessor = $resetBonusPointsProcessor;
    }

    public function assignBonusPoints(ResourceControllerEvent $event): void
    {
        /** @var OrderInterface|BonusPointsAwareInterface $order */
        $order = $event->getSubject();

        Assert::isInstanceOf($order, OrderInterface::class);
        Assert::isInstanceOf($order, BonusPointsAwareInterface::class);

        if (null === $order->getBonusPoints()) {
            return;
        }

        $points = (int) ($order->getBonusPoints());
        $customerBonusPoints = $this->customerBonusPointsContext->getCustomerBonusPoints();

        if (null === $customerBonusPoints) {
            return;
        }

        $this->resetBonusPointsProcessor->process($order);

        $now = new \DateTime();
        $availableBonusPoints = $customerBonusPoints->getSortedNotUsedAndNotExpired($now);

        /** @var BonusPointsInterface $availableBonusPoint */
        foreach ($availableBonusPoints as $availableBonusPoint) {
            if (0 >= $points) {
                break;
            }

            $leftPointsFromPool = $availableBonusPoint->getLeftPointsFromAvailablePool();

            if ($points >= $leftPointsFromPool) {
                $this->bonusPointsCreator->createWithData($customerBonusPoints, $order, $leftPointsFromPool, $availableBonusPoint);

                $points -= $leftPointsFromPool;
                continue;
            }

            $this->bonusPointsCreator->createWithData($customerBonusPoints, $order, $points, $availableBonusPoint);

            break;
        }
    }
}
