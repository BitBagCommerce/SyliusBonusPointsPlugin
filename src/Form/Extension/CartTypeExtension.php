<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Form\Extension;

use BitBag\SyliusBonusPointsPlugin\Context\CustomerBonusPointsContextInterface;
use BitBag\SyliusBonusPointsPlugin\Resolver\BonusPointsResolverInterface;
use BitBag\SyliusBonusPointsPlugin\Validator\Constraints\BonusPointsApply;
use Sylius\Bundle\MoneyBundle\Form\Type\MoneyType;
use Sylius\Bundle\OrderBundle\Form\Type\CartType;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Range;

final class CartTypeExtension extends AbstractTypeExtension
{
    public function __construct(
        private CustomerBonusPointsContextInterface $customerBonusPointsContext,
        private BonusPointsResolverInterface $bonusPointsResolver,
        private CartContextInterface $cartContext,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if (null === $this->customerBonusPointsContext->getCustomerBonusPoints()) {
            return;
        }

        /** @var OrderInterface $cart */
        $cart = $this->cartContext->getCart();

        if (0 === $this->bonusPointsResolver->resolveBonusPoints($cart)) {
            return;
        }

        $builder
            ->add('bonusPoints', MoneyType::class, [
                'required' => false,
                'currency' => false,
                'constraints' => [
                    new Range([
                        'min' => 0,
                        'groups' => ['sylius'],
                    ]),
                    new BonusPointsApply([
                        'groups' => ['sylius'],
                    ]),
                ],
                'data' => null,
            ])
        ;
    }

    public static function getExtendedTypes(): iterable
    {
        return [
            CartType::class,
        ];
    }
}
