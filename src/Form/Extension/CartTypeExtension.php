<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
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
    /** @var CustomerBonusPointsContextInterface */
    private $customerBonusPointsContext;

    /** @var BonusPointsResolverInterface */
    private $bonusPointsResolver;

    /** @var CartContextInterface */
    private $cartContext;

    public function __construct(
        CustomerBonusPointsContextInterface $customerBonusPointsContext,
        BonusPointsResolverInterface $bonusPointsResolver,
        CartContextInterface $cartContext,
    ) {
        $this->customerBonusPointsContext = $customerBonusPointsContext;
        $this->bonusPointsResolver = $bonusPointsResolver;
        $this->cartContext = $cartContext;
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
            ]);
    }

    public static function getExtendedTypes(): iterable
    {
        return [
            CartType::class,
        ];
    }
}
