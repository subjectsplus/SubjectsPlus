# SubjectsPlus v5

## Dependencies

To upgrade dependencies to the latest versions, `composer upgrade && yarn install --force`

## Testing

Test database:

`docker-compose exec web php bin/console doctrine:schema:drop --force -e test`
`docker-compose exec web php bin/console doctrine:migrations:migrate -e test`
`docker-compose exec web php bin/console doctrine:fixtures:load -e test`


To run tests: `php bin/phpunit`

## Translations

To view in a different translation, change the `default_locale` in config/packages/translation.yaml

When you have added some new strings that will need translations, run `php bin/console translation:update --force es` for all applicable locales

To add a translation:

1. Add the translation to translations/messages.LOCALE.xlf for the appropriate locale
2. `php bin/console cache:clear && php bin/console debug:translation LOCALE` to refresh your dev server and output any troubleshooting info