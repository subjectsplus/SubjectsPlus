# SubjectsPlus v5

## Translations

To view in a different translation, change the `default_locale` in config/packages/translation.yaml

When you have added some new strings that will need translations, run `php bin/console translation:update --force es` for all applicable locales

To add a translation:

1. Add the translation to translations/messages.LOCALE.xlf for the appropriate locale
2. `php bin/console cache:clear && php bin/console debug:translation LOCALE` to refresh your dev server and output any troubleshooting info