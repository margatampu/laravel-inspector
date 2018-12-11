# laravel-inspector

[![Latest Version on Packagist](https://img.shields.io/packagist/v/margatampu/aravel-inspector.svg?style=flat-square)](https://packagist.org/packages/margatampu/aravel-inspector)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Total Downloads](https://img.shields.io/packagist/dt/margatampu/aravel-inspector.svg?style=flat-square)](https://packagist.org/packages/margatampu/aravel-inspector)

Watching and observing requests, logs, and listed models to maintain all possible activities. Laravel inspector use to do it and storing data in separate database.

## Installation

Require this package with composer.

```bash
$ composer require margatampu/laravel-inspector
```

## Integration

Package tested and worked with Laravel and Lumen framework (5.7+). 

__Laravel__: After installation using composer finishes up, you'll have to add the following line to your `config/app.php`:

```php
MargaTampu\LaravelInspector\InspectorServiceProvider::class
```

__Lumen__: For Lumen, you'll have to add the following line to your `bootstrap/app.php`:

```php
$app->register(MargaTampu\LaravelInspector\InspectorServiceProvider::class);
```
__Laravel__: Then copy `inspector` config file from laravel-inspector to your config folder:

```bash
$ php artisan vendor:publish --provider="MargaTampu\LaravelInspector\InspectorServiceProvider"
```

__Lumen__: For Lumen, you need to copy file manually to your config folder and enable it in `bootstrap/app.php`:

```php
$app->configure('inspector');
```

After configuration, migrate all necessary database structure from laravel-inspector with (prerequisite setting database in `.env` file):

```bash
$ php artisan migrate
```

To make sure laravel-inspector running securily, you need to generate inspector auth token using console command line with default value:

```bash
$ php artisan inspector:auth
```

It will generate a random key, and you need to [store it in your .env](#setup-env-file) file.

Last step for your integration, you need to add this line to your `routes\api.php` file to use laravel-inspector api route to handle storing data to database.
```php
MargaTampu\LaravelInspector\Inspector::api();
```

## Configuring config file

For your models activity, you need to listing all your models you want to watched. Placed all your models in `config\inspector.php` file inside `models` element.

```php
'models' => [
    'App\User'
],
```

## Setup env file

After installation and some integration, you need to update setup in your `.env` file. Besides database setting, you can add variable:
- `INSPECTOR_AUTHORIZATION`, to store your generated auth token.

Don't forgot to change your `APP_URL` as well with your current application domain url. It is use as api route url to store all your inspector data.

## Go Ahaed

For expert use, you can ignore last step for integration steps for copying inspector API, and copied laravel-inspector api routes manually to your `routes/api.php` file and use your own controller to maintain data before store to database.

Then, you can store your destination url in `.env file` using this variables:

- `INSPECTOR_MODEL_ENDPOINT`, full url to handle model inspector.
- `INSPECTOR_LOG_ENDPOINT`, full url to handle log inspector.
- `INSPECTOR_REQUEST_ENDPOINT`, full url to handle request inspector.

## Queueing Jobs

To prevent inspector slowing your projects, you can queueing inspector jobs using separate queue name `inspector`. You can run single queue worker for it using this command:

```bash
$ php artisan queue:work --tries=1 --queue=inspector
```

## Usage

To use this package, after all integration steps done. You will have your requests, logs and models activity in database.

## Console Command

You did run inspector:auth command before to generate new inspector default auth token. Beside it, we have several commands available:

- Custom inspector auth name

You can custom your own inspector auth name using this command:
```bash
$ php artisan inspector:auth --new
```

- Update name of existing inspector auth

You allowed to rename your existing inpector auth name using: __Note: Replace {$id} with your inspector auth id__

```bash
$ php artisan inspector:auth --name={$id}
```

- Refresh token of existing inspector auth

Like inspector auth name, you also allowed to change token or refresh it using: __Note: Replace {$id} with your inspector auth id__

```bash
$ php artisan inspector:auth --refresh={$id}
```

## Testing
For some laravel/lumen project, this package still not stable in some parts. If you want to test this package, you have several command to test it.

- Run all modules test with a single command

You can run a single command to run test all modules (model, log, and request).
```bash
$ php artisan inspector:test --all
```

- Run model module test only

Since you can enable and disable module in configuration file, you also enable to run single module too.
```bash
$ php artisan inspector:test --model
$ php artisan inspector:test --log
$ php artisan inspector:test --request
```

### Prerequisit for testing modules
To ensure your package test module run smoothly, you need to recheck some configurations:
1. Since the package test using running database (not separated test database), please change your current database to new fresh database or you will need to delete inserted data manually.
2. Queue connection using sync. Use connection besides it will force test to exit.
3. Check if module enable for model, log, and/or request. Disable module will not run test for it.
4. For model module test, package use User class model, so you need to ensure that User model and factory are available for use. But if you disable model module, you can skip this step.
5. For request module test, package use guzzle/http to acces your main page (url('/')), please ensure that url exist to access. But if you disable request module, you can skipt this step too.


## License

This laravel-teams-logging package is available under the MIT license. See the LICENSE file for more info.
