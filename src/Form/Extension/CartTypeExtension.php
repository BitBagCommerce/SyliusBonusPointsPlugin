<?php

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
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
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
        CartContextInterface $cartContext
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

        if ($this->bonusPointsResolver->resolveBonusPoints($cart) === 0) {
            return;
        }

        $builder
            ->add('bonusPoints', MoneyType::class, [
                'required' => false,
                'currency' => false,
                'constraints' => [
                    new Range([
                        'min' => 0.01,
                        'groups' => ['sylius'],
                    ]),
                    new BonusPointsApply([
                        'message' => 'bitbag_sylius_bonus_points.cart.bonus_points.invalid_number',
                        'groups' => ['sylius'],
                    ])
                ]
            ])
        ;
    }

    public function getExtendedTypes(): array
    {
        return [
            CartType::class
        ];
    }
}
