<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Validator\Constraints;

use BitBag\SyliusBonusPointsPlugin\Entity\BonusPointsAwareInterface;
use BitBag\SyliusBonusPointsPlugin\Entity\BonusPointsInterface;
use BitBag\SyliusBonusPointsPlugin\Resolver\BonusPointsResolverInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Webmozart\Assert\Assert;

final class BonusPointsAvailabilityValidator extends ConstraintValidator
{
    /** @var BonusPointsResolverInterface */
    private $bonusPointsResolver;

    /** @var RepositoryInterface */
    private $bonusPointsRepository;

    public function __construct(BonusPointsResolverInterface $bonusPointsResolver, RepositoryInterface $bonusPointsRepository)
    {
        $this->bonusPointsResolver = $bonusPointsResolver;
        $this->bonusPointsRepository = $bonusPointsRepository;
    }

    /**
     * @param OrderInterface|mixed $order
     */
    public function validate($order, Constraint $constraint): void
    {
        Assert::implementsInterface($order, BonusPointsAwareInterface::class);
        Assert::isInstanceOf($order, OrderInterface::class);
        Assert::isInstanceOf($constraint, BonusPointsAvailability::class);

        $usedPoints = $order->getBonusPoints();

        if (null === $usedPoints) {
            /** @var BonusPointsInterface[] $bonusPoints */
            $bonusPoints = $this->bonusPointsRepository->findBy([
                'order' => $order,
                'isUsed' => true,
            ]);

            if ([] === $bonusPoints) {
                return;
            }

            foreach ($bonusPoints as $bonusPoint) {
                $parentBonusPoint = $bonusPoint->getOriginalBonusPoints();

                if (null === $parentBonusPoint ||
                    $parentBonusPoint->isExpired() ||
                    0 === $bonusPoint->getPoints()
                ) {
                    continue;
                }

                $usedPoints += $bonusPoint->getPoints();
            }
        }

        if ($usedPoints > $this->bonusPointsResolver->resolveBonusPoints($order)) {
            $this->context
                ->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
