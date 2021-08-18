<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
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
