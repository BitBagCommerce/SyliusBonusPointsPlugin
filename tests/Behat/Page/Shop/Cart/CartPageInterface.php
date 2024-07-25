<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace Tests\BitBag\SyliusBonusPointsPlugin\Behat\Page\Shop\Cart;

use Sylius\Behat\Page\Shop\Cart\SummaryPageInterface;
use Tests\BitBag\SyliusBonusPointsPlugin\Behat\Behaviour\ContainsErrorInterface;

interface CartPageInterface extends SummaryPageInterface, ContainsErrorInterface
{
    public function getNumberOfAvailableBonusPoints(): string;

    public function applyPoints(string $points): void;

    public function resetPoints(): void;
}
