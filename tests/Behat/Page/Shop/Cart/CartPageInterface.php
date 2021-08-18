<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
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
