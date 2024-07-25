<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
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
