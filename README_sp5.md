# SubjectsPlus v5

## Dependencies

To upgrade dependencies to the latest versions, `composer upgrade && yarn install --force`

## Static analysis

`vendor/bin/php-cs-fixer fix` to fix issues.  Append `--verbose --dry-run` to learn more about the issues before they are automatically fixed.

TODO: add jslint

## Themes

To add a theme, create a folder in the templates directory with the same structure as the templates directory

## Testing

Test database:

`docker-compose exec web php bin/console doctrine:schema:drop --force -e test`
`docker-compose exec web php bin/console doctrine:migrations:migrate -e test`
`docker-compose exec web php bin/console doctrine:fixtures:load -e test`


To run tests: `php bin/phpunit`

## Javascripts

To add a js library:

1. `yarn add name_of_package`
2. `yarn encore dev`

If you need some js for a particular page: https://symfony.com/doc/current/frontend/encore/simple-example.html#page-specific-javascript-or-css-multiple-entries


## Building for production

`yarn encore production`

## Translations

To view in a different translation, change the `default_locale` in config/packages/translation.yaml

When you have added some new strings that will need translations, run `php bin/console translation:update --force es` for all applicable locales

To add a translation:

1. Add the translation to translations/messages.LOCALE.xlf for the appropriate locale
2. `php bin/console cache:clear && php bin/console debug:translation LOCALE` to refresh your dev server and output any troubleshooting info

## New/modified functionality

My goal was to more-or-less keep parity in:
* Display (with the SPLUX theme)
* Database + Configuration (so an existing db and config.php could use this with no changes)

On all other matters, I tried to
* follow Symfony best practices as documented on their Web site
* keep code DRY
* strong test coverage

A few features didn't make it over:
* 

A few features were added