# Valet Assistant

[![Latest Version on Packagist](https://img.shields.io/packagist/v/gbuckingham89/valet-assistant.svg?style=flat-square)](https://packagist.org/packages/gbuckingham89/valet-assistant)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/gbuckingham89/valet-assistant/Tests?label=tests)](https://github.com/gbuckingham89/valet-assistant/actions?query=workflow%3Atests+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/gbuckingham89/valet-assistant.svg?style=flat-square)](https://packagist.org/packages/gbuckingham89/valet-assistant)

A Laravel package for using [Laravel Valet](https://laravel.com/docs/valet) within another Laravel application - e.g. getting a list of sites served by Valet.

_This package will only fully function when used on a computer that has Laravel Valet installed._

**Looking for a UI for Laravel Valet? Take a look at [gbuckingham89/valet-launchpad](https://github.com/gbuckingham89/valet-launchpad).**

## Installation

You can install the package via composer:

```bash
composer require gbuckingham89/valet-assistant
```

You can publish the Laravel config file with:

```bash
php artisan vendor:publish --tag="valet-assistant-config"
```

## Usage

Use the [Laravel container](https://laravel.com/docs/container) to resolve a copy of `Gbuckingham89\ValetAssistant\ValetAssistant` however you feel is best - e.g. dependency injection or via `app()->make()`. 

There is also a Facade, `Gbuckingham89\ValetAssistant\Facade\ValetAssistant`, if that's your preference.

Once you have your instance, you can use the following methods:

**Get a list of all the projects served by Laravel Valet:**

```php
$valetSites = $valetAssistant()->projects();
```

This returns a Collection of "Project" objects (representing a directory on your local machine). Each Project will have one or more "Site" objects (representing a URL that the project is served under). Take a look at the source code or use auto-completion in your IDE to learn more about the data structure. 


**Check is Valet is installed (and accessible):**

```php
$valetIsInstalled = $valetAssistant()->isInstalled();
```

This simply returns a boolean value indicating if Valet is installed, or not.

## Troubleshooting

### Valet not installed

If you see errors about Valet not being installed (but you're sure that it is, and you see output by running `which valet` from your local terminal) it's likely that the user running your PHP script doesn't know where the Valet binary is located.

There are two ways to resolve this;

1. Add `/Users/[local-username]/.composer/vendor/bin` to the PATH file for the user running the PHP script _(remember to insert the username that Valet is installed under)_
2. Find the value of your PATH by running `echo $PATH` from your local Terminal. Ensure you have published the config file (see above), then add an entry to your `.env` file with the key `VALET_ASSISTANT_ENV_PATH` and the value of your local PATH. This PATH value will then be used by your PHP script.

_**Please do your own research and consider any security implications of giving PHP access to additional PATH directories.**_

## Testing

You can run the test suite by calling `./vendor/bin/phpunit` or `composer test`.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [George Buckingham](https://github.com/gbuckingham89)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
