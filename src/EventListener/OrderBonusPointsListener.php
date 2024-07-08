<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\EventListener;

use BitBag\SyliusBonusPointsPlugin\Context\CustomerBonusPointsContextInterface;
use BitBag\SyliusBonusPointsPlugin\Creator\BonusPointsCreatorInterface;
use BitBag\SyliusBonusPointsPlugin\Entity\BonusPointsAwareInterface;
use BitBag\SyliusBonusPointsPlugin\Entity\BonusPointsInterface;
use BitBag\SyliusBonusPointsPlugin\Processor\ResetOrderBonusPointsProcessorInterface;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Core\Model\OrderInterface;
use Webmozart\Assert\Assert;

final class OrderBonusPointsListener
{
    public function __construct(
        private readonly BonusPointsCreatorInterface $bonusPointsCreator,
        private readonly CustomerBonusPointsContextInterface $customerBonusPointsContext,
        private readonly ResetOrderBonusPointsProcessorInterface $resetBonusPointsProcessor,
    ) {
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

        $points = $order->getBonusPoints();
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

            if (0 >= $leftPointsFromPool) {
                continue;
            }

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
