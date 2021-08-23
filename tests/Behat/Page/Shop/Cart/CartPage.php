<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

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
