# SubjectsPlus v5
## Upgrading an existing system

* Use git to checkout this branch, maintaining your existing config.php, etc.
* `docker-compose up -d`
* Make sure there is some good data in the database -- I just used mysqldump to get data from our production SubjectsPlus
* `composer install`
* `yarn encore dev`
* Change the assetPath line in config.php to: `$AssetPath = $BaseURL.'build/assets/';`

## Development notes
### Dependencies

To upgrade dependencies to the latest versions, `composer upgrade && yarn install --force`

### Static analysis

`vendor/bin/php-cs-fixer fix` to fix issues.  Append `--verbose --dry-run` to learn more about the issues before they are automatically fixed.

TODO: add jslint

### Themes

To add a theme, create a folder in the templates directory with the same structure as the templates directory

### Testing

To run tests: `docker-compose exec web ./run_tests.sh`

### Javascripts

To add a js library:

1. `yarn add name_of_package`
2. `yarn encore dev`

If you need some js for a particular page: https://symfony.com/doc/current/frontend/encore/simple-example.html#page-specific-javascript-or-css-multiple-entries


### Building for production

`yarn encore production`

### Translations

To view in a different translation, change the `default_locale` in config/packages/translation.yaml

When you have added some new strings that will need translations, run `php bin/console translation:update --force es` for all applicable locales

To add a translation:

1. Add the translation to translations/messages.LOCALE.xlf for the appropriate locale
2. `php bin/console cache:clear && php bin/console debug:translation LOCALE` to refresh your dev server and output any troubleshooting info

### To migrate a screen to Symfony

* Create a new controller *or* add a method to an existing controller (e.g. `php bin/console make:controller Patron\TalkbackController`)
* Modify the route annotation to match the current route(s) used for the screen in SP4
* Add any necessary logic to your controller.  Note that Symfony recommends "skinny controllers", with most of the logic happening in other objects.
  * You can use any functions or classes from SP4 as well
* For any logic shared between controllers, create:
  * a file in tests/Service/ with some tests for your logic
  * a file in src/Service with your actual logic -- this can be just a plain PHP object which can then be injected into your controllers
* For any sophisticated queries:
  * add any data necessary for testing to the fixtures
  * add some tests to the tests/Entity or tests/Repository directory
  * add your logic to an Entity or Repository
* Add templates
* Add javascripts.
  * Throw the shared ones into the assets/javascripts directory (autocomplete.js is a good example)
  * The ones that are just used for one page: add to the patron or staff directory, as needed.  Then add an addEntry line to webpack.config.js and a
  `{{encore_entry_script_tags}}` tag to your template

### New/modified functionality

My goal was to more-or-less keep parity in:
* Display (with the SPLUX theme)
* Database + Configuration (so an existing db and config.php could use this with no changes)

On all other matters, I tried to
* follow Symfony best practices as documented on their Web site
* keep code DRY
* include automated tests
