<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusBonusPointsPlugin\Menu;

use Knp\Menu\ItemInterface;
use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

final class AdminMenuBuilder
{
    public function addItems(MenuBuilderEvent $event): void
    {
        /** @var ItemInterface $menu */
        $menu = $event->getMenu()->getChild('marketing');

        $menu
            ->addChild('bonus_points_strategies', ['route' => 'bitbag_sylius_bonus_points_admin_bonus_points_strategy_index'])
            ->setLabel('bitbag_sylius_bonus_points.ui.bonus_points_strategies')
            ->setLabelAttribute('icon', 'bullhorn')
        ;
    }
}
