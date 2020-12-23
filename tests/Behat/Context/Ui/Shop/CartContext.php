<?php

declare(strict_types=1);

namespace Tests\BitBag\SyliusBonusPointsPlugin\Behat\Context\Ui\Shop;

use Behat\Behat\Context\Context;
use BitBag\SyliusBonusPointsPlugin\Repository\BonusPointsStrategyRepositoryInterface;
use Sylius\Behat\NotificationType;
use FriendsOfBehat\PageObjectExtension\Page\SymfonyPageInterface;
use Sylius\Behat\Service\NotificationCheckerInterface;
use Sylius\Behat\Service\Resolver\CurrentPageResolverInterface;
use Sylius\Behat\Service\SharedStorageInterface;
use Tests\BitBag\SyliusBonusPointsPlugin\Behat\Page\Admin\BonusPointsStrategy\CreatePageInterface;
use Tests\BitBag\SyliusBonusPointsPlugin\Behat\Page\Admin\BonusPointsStrategy\IndexPageInterface;
use Tests\BitBag\SyliusBonusPointsPlugin\Behat\Page\Admin\BonusPointsStrategy\UpdatePageInterface;
use Tests\BitBag\SyliusBonusPointsPlugin\Behat\Page\Shop\Cart\CartPageInterface;
use Webmozart\Assert\Assert;

final class CartContext implements Context
{
    /** @var CartPageInterface */
    private $cartPage;

    public function __construct(CartPageInterface $cartPage)
    {
        $this->cartPage = $cartPage;
    }

    /**
     * @Then I should see that I have :points bonus points
     */
    public function iShouldSeeThatIHaveBonusPoints(string $points): void
    {
        $availableBonusPoints = $this->cartPage->getNumberOfAvailableBonusPoints();

        Assert::same($availableBonusPoints, $points);
    }

    /**
     * @Then I want to use :points bonus points
     */
    public function iWantToUseBonusPoint(string $points)
    {
        $this->cartPage->applyPoints($points);
    }

    /**
     * @Then I should be notified that I do not have enough bonus points
     */
    public function iShouldBeNotifiedThatIDoNotHaveEnoughBonusPoints()
    {
        Assert::true($this->cartPage->containsErrorWithMessage(sprintf('You do not have enough bonus points.')));
    }

    /**
     * @Then I should be notified that this number must be natural number, greater than or equal to 1
     */
    public function iShouldBeNotifiedThatThisNumberMustBeNaturalNumberGreaterThanOrEqualTo()
    {
        Assert::true($this->cartPage->containsErrorWithMessage(sprintf('This number must be natural number, greater than or equal to 1.')));
    }
}
