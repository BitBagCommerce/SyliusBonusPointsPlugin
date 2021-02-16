<?php

declare(strict_types=1);

namespace Tests\BitBag\SyliusBonusPointsPlugin\Behat\Page\Shop\Cart;

use Sylius\Behat\Page\Shop\Cart\SummaryPage;
use Tests\BitBag\SyliusBonusPointsPlugin\Behat\Behaviour\ContainsErrorTrait;

class CartPage extends SummaryPage implements CartPageInterface
{
    use ContainsErrorTrait;

    public function getNumberOfAvailableBonusPoints(): string
    {
        return $this->getDocument()->findById('value-bonus-points')->getText();
    }

    public function applyPoints(string $points): void
    {
        $this->getDocument()->fillField('sylius_cart_bonusPoints', $points);

        $this->getDocument()->findButton('Apply')->click();
    }

    public function resetPoints(): void
    {
        $this->getDocument()->clickLink('reset-bonus-points');
    }
}
