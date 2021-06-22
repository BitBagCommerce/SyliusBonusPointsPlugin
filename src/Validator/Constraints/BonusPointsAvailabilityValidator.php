<?php

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Validator\Constraints;

use BitBag\SyliusBonusPointsPlugin\Entity\BonusPointsAwareInterface;
use BitBag\SyliusBonusPointsPlugin\Entity\BonusPointsInterface;
use BitBag\SyliusBonusPointsPlugin\Resolver\BonusPointsResolverInterface;
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
     * @param BonusPointsAwareInterface $order
     */
    public function validate($order, Constraint $constraint): void
    {
        Assert::isInstanceOf($order, BonusPointsAwareInterface::class);
        Assert::isInstanceOf($constraint, BonusPointsAvailability::class);

        $points = null !== $order->getBonusPoints() ? (int) ($order->getBonusPoints()) : null;

        if (null === $points) {
            /** @var BonusPointsInterface $bonusPoints */
            $bonusPoints = $this->bonusPointsRepository->findOneBy([
                'order' => $order,
                'isUsed' => true,
            ]);

            if (null === $bonusPoints) {
                return;
            }

            $points = $bonusPoints->getPoints();
        }

        if ($points > $this->bonusPointsResolver->resolveBonusPoints($order)) {
            $this->context
                ->buildViolation($constraint->message)
                ->addViolation()
            ;
        }
    }
}
