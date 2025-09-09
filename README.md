# Site Guard

Protect your site from random visitors with a simple plain-text password guard, configurable in your `.env` file.

Apply the middleware to your middleware of choice, and this will prevent visiting those routes when desired. Instead, a password prompt will be displayed:

<img src="screenshot.png" alt="A simple password prompt.">

## Installation

You can install the package with `composer`:

```bash
composer require mylonia/site-guard
```

## Usage

First, set these environment variables:

```dotenv
SITE_GUARD_PASSWORD=your-passphrase-here
```

You can register the middleware under the conditions of your liking, for example in `AppServiceProvider`:

```php
use Mylonia\SiteGuard\SiteGuardMiddleware;

public function boot(Router $router): void
{
    if (! $this->app->environment('production')) {
        $router->pushMiddlewareToGroup('web', SiteGuardMiddleware::class);
    }
}
```

This ensures that the production website is unaffected, but any potential `local` or `staging` setup will display the message.

## Customization

You can also exclude particular routes by customising the `config` file.

```bash
php artisan vendor:publish --provider="Mylonia\SiteGuard\SiteGuardServiceProvider"
```

This will publish the custom `views` and the `site-guard` config file. You can customise `excluded_routes` to exclude particular routes. 

By default, all `site_guard.*` routes are excluded, but you can add more this way. (You can use wildcards.)

## Testing

```bash
composer test
```

To run all steps (larastan, rector, pint and test suite):

```bash
composer verify
```