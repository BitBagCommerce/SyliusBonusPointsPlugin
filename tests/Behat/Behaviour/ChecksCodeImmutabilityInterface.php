<?php

declare(strict_types=1);

namespace Tests\BitBag\SyliusBonusPointsPlugin\Behat\Behaviour;

interface ChecksCodeImmutabilityInterface
{
    public function isCodeDisabled(): bool;
}
