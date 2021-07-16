<?php

declare(strict_types=1);

namespace Tests\BitBag\SyliusBonusPointsPlugin\Behat\Context\Ui\Admin;

use Behat\Behat\Context\Context;
use BitBag\SyliusBonusPointsPlugin\Repository\BonusPointsStrategyRepositoryInterface;
use FriendsOfBehat\PageObjectExtension\Page\SymfonyPageInterface;
use Sylius\Behat\NotificationType;
use Sylius\Behat\Service\NotificationCheckerInterface;
use Sylius\Behat\Service\Resolver\CurrentPageResolverInterface;
use Sylius\Behat\Service\SharedStorageInterface;
use Tests\BitBag\SyliusBonusPointsPlugin\Behat\Page\Admin\BonusPointsStrategy\CreatePageInterface;
use Tests\BitBag\SyliusBonusPointsPlugin\Behat\Page\Admin\BonusPointsStrategy\IndexPageInterface;
use Tests\BitBag\SyliusBonusPointsPlugin\Behat\Page\Admin\BonusPointsStrategy\UpdatePageInterface;
use Webmozart\Assert\Assert;

final class BonusPointsStrategyContext implements Context
{
    /** @var SharedStorageInterface */
    private $sharedStorage;

    /** @var CurrentPageResolverInterface */
    private $currentPageResolver;

    /** @var NotificationCheckerInterface */
    private $notificationChecker;

    /** @var IndexPageInterface */
    private $indexPage;

    /** @var CreatePageInterface */
    private $createPage;

    /** @var UpdatePageInterface */
    private $updatePage;

    /** @var BonusPointsStrategyRepositoryInterface */
    private $bonusPointsStrategyRepository;

    public function __construct(
        SharedStorageInterface $sharedStorage,
        CurrentPageResolverInterface $currentPageResolver,
        NotificationCheckerInterface $notificationChecker,
        IndexPageInterface $indexPage,
        CreatePageInterface $createPage,
        UpdatePageInterface $updatePage,
        BonusPointsStrategyRepositoryInterface $bonusPointsStrategyRepository
    ) {
        $this->sharedStorage = $sharedStorage;
        $this->currentPageResolver = $currentPageResolver;
        $this->notificationChecker = $notificationChecker;
        $this->indexPage = $indexPage;
        $this->createPage = $createPage;
        $this->updatePage = $updatePage;
        $this->bonusPointsStrategyRepository = $bonusPointsStrategyRepository;
    }

    /**
     * @When I go to the create bonus points strategy page
     */
    public function iGoToTheCreateBonusPointsStrategyPage(): void
    {
        $adminUser = $this->sharedStorage->get('administrator');
        $this->createPage->open();
    }

    /**
     * @When I go to update bonus points strategy :code page
     */
    public function iGoToUpdateBonusPointsStrategyPage(string $code): void
    {
        $bonusPointsStrategy = $this->bonusPointsStrategyRepository->findOneBy(['code' => $code]);

        $this->updatePage->open(['id' => $bonusPointsStrategy->getId()]);
    }

    /**
     * @When I delete a :name bonus points strategy
     */
    public function iDeleteABonusPointsStrategy(string $name): void
    {
        $this->indexPage->open();

        $this->resolveCurrentPage()->deleteBonusPointsStrategy($name);
    }

    /**
     * @Then I fill with :value field :fieldName
     */
    public function iFillWithField(string $value, string $fieldName): void
    {
        $this->resolveCurrentPage()->fillField($fieldName, $value);
    }

    /**
     * @When I name it :configurationName
     * @Then I fill configuration name with :configurationName
     */
    public function iNameIt(string $configurationName): void
    {
        $this->resolveCurrentPage()->fillName($configurationName);
    }

    /**
     * @Then I select :option as calculator
     */
    public function iSelectAsCalculator(string $option): void
    {
        $this->resolveCurrentPage()->selectOption('Calculator type', $option);
    }

    /**
     * @Then I check that it is a deduct bonus points
     */
    public function iCheckThatItIsADeductBonusPoints(): void
    {
        $this->resolveCurrentPage()->checkField('Is deduct bonus points');
    }

    /**
     * @Given I enable it
     */
    public function iEnableIt(): void
    {
        $this->resolveCurrentPage()->enable();
    }

    /**
     * @Given I add the :ruleType rule and choose :taxon taxon
     */
    public function iAddTheRuleConfiguredWithCountAndAsDateModifier(string $ruleType, string $taxon): void
    {
        $this->resolveCurrentPage()->addRule($ruleType);

        $this->resolveCurrentPage()->selectAutocompleteRuleOption('Taxons', $taxon);
    }

    /**
     * @When I add it
     * @When I try to add it
     */
    public function iAddIt(): void
    {
        $this->createPage->create();
    }

    /**
     * @When I update it
     * @When I try to update it
     */
    public function iUpdateIt(): void
    {
        $this->updatePage->update();
    }

    /**
     * @Then I should be notified that the bonus points strategy has been created
     */
    public function iShouldBeNotifiedThatTheBonusPointsStrategyHasBeenCreated(): void
    {
        $this->notificationChecker->checkNotification(
            'Bonus points strategy has been successfully created.',
            NotificationType::success()
        );
    }

    /**
     * @Then I should be notified that the bonus points strategy has been successfully deleted
     */
    public function iShouldBeNotifiedThatTheBonusPointsStrategySuccessfullyHasBeenDeleted(): void
    {
        $this->notificationChecker->checkNotification(
            'Bonus points strategy has been successfully deleted.',
            NotificationType::success()
        );
    }

    /**
     * @Then I should be notified that the bonus points strategy has been successfully updated
     */
    public function iShouldBeNotifiedThatTheAutomaticBlacklistingConfigurationSuccessfullyHasBeenUpdated(): void
    {
        $this->notificationChecker->checkNotification(
            'Bonus points strategy has been successfully updated.',
            NotificationType::success()
        );
    }

    /**
     * @Then :name should no longer exist in the bonus points strategy registry
     */
    public function bonusPointsStrategyShouldNotExistInTheRegistry(string $name): void
    {
        $this->indexPage->open();

        Assert::false($this->indexPage->isSingleResourceOnPage(['name' => $name]));
    }

    /**
     * @Then the :code should appear in the registry
     */
    public function theBonusPointsStrategyShouldAppearInTheRegistry(string $code): void
    {
        $this->indexPage->open();

        Assert::true($this->indexPage->isSingleResourceOnPage(['code' => $code]));
    }

    /**
     * @Given I change last rule count with :count
     */
    public function iChangeLastRuleCountWith(string $count): void
    {
        $this->resolveCurrentPage()->fillRuleOption('Count', $count);
    }

    /**
     * @return IndexPageInterface|CreatePageInterface|UpdatePageInterface|SymfonyPageInterface
     */
    private function resolveCurrentPage(): SymfonyPageInterface
    {
        return $this->currentPageResolver->getCurrentPageWithForm([
            $this->indexPage,
            $this->createPage,
            $this->updatePage,
        ]);
    }
}
