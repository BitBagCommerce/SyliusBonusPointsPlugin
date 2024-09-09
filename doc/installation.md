# Installation

## Overview:
GENERAL
- [Requirements](#requirements)
- [Composer](#composer)
- [Basic configuration](#basic-configuration)
--- 
BACKEND
- [Entities](#entities)
    - [Attribute mapping](#attribute-mapping)
    - [XML mapping](#xml-mapping)
---
FRONTEND
- [Templates](#templates)
---
ADDITIONAL
- [Known Issues](#known-issues)
---

## Requirements:
We work on stable, supported and up-to-date versions of packages. We recommend you to do the same.

| Package       | Version         |
|---------------|-----------------|
| PHP           | \>8.0           |
| sylius/sylius | 1.12.x - 1.13.x |
| MySQL         | \>= 5.7         |
| NodeJS        | \>= 18.x        |

## Composer:
```bash
composer require bitbag/bonus-points-plugin
```

## Basic configuration:
Add plugin dependencies to your `config/bundles.php` file:

```php
# config/bundles.php

return [
    ...
    BitBag\SyliusBonusPointsPlugin\BitBagSyliusBonusPointsPlugin::class => ['all' => true],
];
```

Import required config in your `config/packages/_sylius.yaml` file:

```yaml
# config/packages/_sylius.yaml

imports:
    ...
    - { resource: "@BitBagSyliusBonusPointsPlugin/Resources/config/config.yml" }
```

Import routing in your `config/routes.yaml` file:
```yaml
# config/routes.yaml

bitbag_sylius_bonus_points_plugin:
    resource: "@BitBagSyliusBonusPointsPlugin/Resources/config/routing.yml"
```

## Entities
You can implement entity configuration by using both xml-mapping and attribute-mapping. Depending on your preference, choose either one or the other:
### Attribute mapping
- [Attribute mapping configuration](installation/attribute-mapping.md)
### XML mapping
- [XML mapping configuration](installation/xml-mapping.md)

### Update your database
First, please run legacy-versioned migrations by using command:
```bash
bin/console doctrine:migrations:migrate
```

After migration, please create a new diff migration and update database:
```bash
bin/console doctrine:migrations:diff
bin/console doctrine:migrations:migrate
```
**Note:** If you are running it on production, add the `-e prod` flag to this command.

### Clear application cache by using command:
```bash
bin/console cache:clear
```
**Note:** If you are running it on production, add the `-e prod` flag to this command.

## Templates
Copy required templates into correct directories in your project.

**AdminBundle** (`templates/bundles/SyliusAdminBundle`):
```
vendor/bitbag/bonus-points-plugin/tests/Application/templates/bundles/SyliusAdminBundle/Order/Show/Summary/_totals.html.twig
```

**ShopBundle** (`templates/bundles/SyliusShopBundle`):
```
vendor/bitbag/bonus-points-plugin/tests/Application/templates/bundles/SyliusShopBundle/Cart/Summary/_items.html.twig
vendor/bitbag/bonus-points-plugin/tests/Application/templates/bundles/SyliusShopBundle/Cart/Summary/_totals.html.twig
vendor/bitbag/bonus-points-plugin/tests/Application/templates/bundles/SyliusShopBundle/Common/Order/Table/_totals.html.twig
```

## Known issues
### Translations not displaying correctly
For incorrectly displayed translations, execute the command:
```bash
bin/console cache:clear
```
### Bonus points are not charged
- When configuring the rules for accruing bonus points, select those categories to which the items are assigned directly.

- If you want the points to be credited for each item, select all available categories.
