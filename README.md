# laravel-inspector

[![Latest Version on Packagist](https://img.shields.io/packagist/v/margatampu/aravel-inspector.svg?style=flat-square)](https://packagist.org/packages/margatampu/aravel-inspector)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Total Downloads](https://img.shields.io/packagist/dt/margatampu/aravel-inspector.svg?style=flat-square)](https://packagist.org/packages/margatampu/aravel-inspector)

INSPECTOR_MODEL_ENDPOINT=
INSPECTOR_LOG_ENDPOINT=
INSPECTOR_REQUEST_ENDPOINT=

// check guzzle installation



Watching and observing requests, logs, and listed models to maintain all possible acivities. Laravel inspector use to do it and storing data in separate database.

## Installation

Require this package with composer.

```bash
$ composer require margatampu/laravel-inspector
```

## Integration
Package tested and worked with Laravel and Lumen framework (5.7+). 

After installation using composer finishes up, you'll have to add the following line to your `config/app.php`:

```php
MargaTampu\LaravelInspector\InspectorServiceProvider::class
```

Additionally, you allowed to have the config file copied over:

```bash
$ php artisan vendor:publish --provider="MargaTampu\LaravelInspector\InspectorServiceProvider"
```

And migrate the necessary database structure from laravel-inspector with:

```bash
$ php artisan migrate
```

To make sure laravel-inspector running securily, you need to generate inspector auth token using console command line with default value:

```bash
$ php artisan inspector:auth
```

Or, custom your own inspector auth name using this command:
```bash
$ php artisan inspector:auth --new
```

You also allowed to rename your inspector auth name using console command line:

```bash
$ php artisan inspector:auth --name={$id}
```

Or maybe refresh your old inspector auth token using:

```bash
$ php artisan inspector:auth --refresh={$id}
```

__Note: Replace {$id} with your inspector auth id__

Last step for your integration, you need to add this line to your `routes\api.php` file to use laravel-inspector api route to handle storing data to database.
```php
MargaTampu\LaravelInspector\Inspector::api();
```

## Configuring config file
For your models activity, you need to listing all your models you want to watching. Placing all models in `config\inspector.php` file inside `models` element.

```php
'models' => [
    'App\User'
],
```

## Setup env file
You can store your generated auth token in `.env` file using `INSPECTOR_AUTHORIZATION` variable.


## Go ahaed
For expert use, you can ignore last step for integration steps, and copied laravel-inspector api routes manually to your `routes/api.php` file and use your own controller to main data before store to database.

## Usage
To use this package, after all integration steps done. You will have your requests, logs and models activity in database.

## Next Release
- Return the data with appropriate format.

## License

This laravel-teams-logging package is available under the MIT license. See the LICENSE file for more info.

