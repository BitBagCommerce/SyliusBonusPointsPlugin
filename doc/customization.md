## Customization
***

### Available services you can [decorate](https://symfony.com/doc/current/service_container/service_decoration.html) and forms you can [extend](http://symfony.com/doc/current/form/create_form_type_extension.html)
```bash
$ bin/console debug:container | grep bitbag_sylius_bonus_points
```

### Parameters you can override in your parameters.yml(.dist) file
```bash
$ bin/console debug:container --parameters | grep bitbag
```

## Testing
***
```bash
$ composer install
$ cd tests/Application
$ yarn install
$ yarn run gulp
$ bin/console assets:install public -e test
$ bin/console doctrine:schema:create -e test

$ bin/console server:run 127.0.0.1:8000 -d public -e test
OR
$ symfony server:start -d --dir=public

$ open http://127.0.0.1:8000
$ vendor/bin/behat
$ vendor/bin/phpspec run
```
