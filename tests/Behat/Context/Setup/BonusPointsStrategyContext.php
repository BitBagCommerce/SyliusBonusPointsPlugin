<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace Tests\BitBag\SyliusBonusPointsPlugin\Behat\Context\Setup;

use Behat\Behat\Context\Context;
use BitBag\SyliusBonusPointsPlugin\Calculator\PerOrderPriceCalculator;
use BitBag\SyliusBonusPointsPlugin\Entity\BonusPointsStrategyInterface;
use BitBag\SyliusBonusPointsPlugin\Entity\BonusPointsStrategyRuleInterface;
use BitBag\SyliusBonusPointsPlugin\Repository\BonusPointsStrategyRepositoryInterface;
use Doctrine\Persistence\ObjectManager;
use Sylius\Behat\Service\SharedStorageInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\TaxonInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;

final class BonusPointsStrategyContext implements Context
{
    public function __construct(
        private FactoryInterface $bonusPointsStrategyFactory,
        private FactoryInterface $bonusPointsStrategyRuleFactory,
        private SharedStorageInterface $sharedStorage,
        private ObjectManager $objectManager,
        private BonusPointsStrategyRepositoryInterface $bonusPointsStrategyRepository,
        private TaxonRepositoryInterface $taxonRepository,
    ) {
    }

    /**
     * @Given there is bonus points strategy with code :code and name :name with rule :ruleType with :taxon taxon
     */
    public function thereIsABonusPointsStrategyWithRuleWithTaxon(
        string $code,
        string $name,
        string $ruleType,
        string $taxon,
    ): void {
        $bonusPointsStrategy = $this->createBonusPointsStrategy($code, $name);

        $this->createBonusPointsStrategyRule($bonusPointsStrategy, $ruleType, $taxon);

        $this->objectManager->persist($bonusPointsStrategy);
        $this->objectManager->flush();
    }

    /**
     * @Given the bonus points strategy :code admits :points points per one currency
     */
    public function theAdmitsPointsPerOneCurrency(string $code, string $points): void
    {
        /** @var BonusPointsStrategyInterface $bonusPointsStrategy */
        $bonusPointsStrategy = $this->bonusPointsStrategyRepository->findOneBy(['code' => $code]);

        $bonusPointsStrategy->setCalculatorConfiguration(['numberOfPointsEarnedPerOneCurrency' => $points]);

        $this->objectManager->persist($bonusPointsStrategy);
        $this->objectManager->flush();
    }

    /**
     * @Given I change bonus points strategy :code calculator type on :calculatorType with :percent percent to calculate points
     */
    public function iChangeBonusPointsStrategyCalculatorTypeOn(string $code, string $calculatorType, string $percent): void
    {
        /** @var BonusPointsStrategyInterface $bonusPointsStrategy */
        $bonusPointsStrategy = $this->bonusPointsStrategyRepository->findOneBy(['code' => $code]);

        $calculatorType = str_replace(' ', '_', strtolower($calculatorType));

        $configuration = [];

        /** @var ChannelInterface $channel */
        $channel = $this->sharedStorage->get('channel');
        $configuration[$channel->getCode()] = ['percentToCalculatePoints' => ((int) $percent / 100)];

        $bonusPointsStrategy->setCalculatorType($calculatorType);
        $bonusPointsStrategy->setCalculatorConfiguration($configuration);

        $this->objectManager->persist($bonusPointsStrategy);
        $this->objectManager->flush();
    }

    private function createBonusPointsStrategy(string $code, string $name): BonusPointsStrategyInterface
    {
        /** @var BonusPointsStrategyInterface $bonusPointsStrategy */
        $bonusPointsStrategy = $this->bonusPointsStrategyFactory->createNew();

        $bonusPointsStrategy->setCode($code);
        $bonusPointsStrategy->setName($name);
        $bonusPointsStrategy->enable();
        $bonusPointsStrategy->setCalculatorType(PerOrderPriceCalculator::TYPE);

        return $bonusPointsStrategy;
    }

    private function createBonusPointsStrategyRule(BonusPointsStrategyInterface $bonusPointsStrategy, string $ruleType, string $taxonName): void
    {
        $ruleType = str_replace(' ', '_', strtolower($ruleType));
        $taxonName = strtolower($taxonName);

        $configurationTaxons = [];
        $taxons = $this->taxonRepository->findBy(['code' => $taxonName]);

        /** @var TaxonInterface $taxon */
        foreach ($taxons as $taxon) {
            $configurationTaxons[] = $taxon->getCode();
        }

        /** @var BonusPointsStrategyRuleInterface $rule */
        $rule = $this->bonusPointsStrategyRuleFactory->createNew();
        $rule->setType($ruleType);
        $rule->setConfiguration(['taxons' => $configurationTaxons]);
        $bonusPointsStrategy->addRule($rule);

        $this->objectManager->persist($rule);
    }
}
