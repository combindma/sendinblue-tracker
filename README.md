# Sendinblue Tracker SDK For PHP

[![Latest Version on Packagist](https://img.shields.io/packagist/v/combindma/sendinblue-tracker.svg?style=flat-square)](https://packagist.org/packages/combindma/sendinblue-tracker)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/combindma/sendinblue-tracker/Check%20&%20fix%20styling?label=code%20style)](https://github.com/combindma/sendinblue-tracker/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/combindma/sendinblue-tracker.svg?style=flat-square)](https://packagist.org/packages/combindma/sendinblue-tracker)

## Installation
You can install the package via composer:

```bash
composer require combindma/sendinblue-tracker
```

You can publish the config file with:
```bash
php artisan vendor:publish --provider="Combindma\SendinBlueTracker\SendinBlueTrackerServiceProvider" --tag="sendinblue-tracker-config"
```

This is the contents of the published config file:

```php
return [
    'tracker_id' => env('SENDINBLUE_TRACKER_ID', null),

    /*
     * The key under which data is saved to the session with flash.
     */
    'sessionKey' => env('SENDINBLUE_TRACKER_SESSION_KEY', strtolower(config('app.name')).'_sendinbluetracker'),

    /*
     * Enable or disable script rendering. Useful for local development.
     */
    'enabled' => env('ENABLE_SENDINBLUE_TRACKER', false),
];
```

## Usage

### Embed in Blade
First you'll need to include Sendinblue Tracker's script.

```html
<!DOCTYPE html>
<html>
<head>
    @include('sendinbluetracker::head')
</head>
<body>
    @include('sendinbluetracker::body')
    /*
    * Content
    */
</body>
```

#### Identify
The is the primary way to create a new user within sendinblue or update an exsisting one. The primary way of indentifying users is via their email address.

```php
SendinBlueTracker::identify('email@email.com');
```

#### Event
The next method is how we fire an event within sendinblue, this can be used to trigger workflows and other types of automation.

```php
SendinBlueTracker::event(
    'eventName',
    // User Data
    [
      'EMAIL' => 'email@email.com',
      'FIRSTNAME' => 'XXXXX'
    ],
  	// Event Data
    [
      'CTA_URL' => 'https://www.example.com',
      'COST' => '20.00'
    ]
);
```

#### Flashing data for the next request
The package can also set data to render on the next request. This is useful for setting data after an internal redirect.


```php
SendinBlueTracker::flash(
    'eventName',
    // User Data
    [
      'EMAIL' => 'email@email.com',
      'FIRSTNAME' => 'XXXXX'
    ],
  	// Event Data
    [
      'CTA_URL' => 'https://www.example.com',
      'COST' => '20.00'
    ]
);
```

## Contributing
Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Credits
- [Combind](https://github.com/Combindma)
- [All Contributors](../../contributors)

## License
The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
