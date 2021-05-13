#!/bin/bash

php bin/console doctrine:schema:update --force -e test
php bin/console doctrine:fixtures:load -e test -n
php bin/phpunit
