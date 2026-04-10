# phpnomad/symfony-event-dispatcher-integration

[![Latest Version](https://img.shields.io/packagist/v/phpnomad/symfony-event-dispatcher-integration.svg)](https://packagist.org/packages/phpnomad/symfony-event-dispatcher-integration)
[![Total Downloads](https://img.shields.io/packagist/dt/phpnomad/symfony-event-dispatcher-integration.svg)](https://packagist.org/packages/phpnomad/symfony-event-dispatcher-integration)
[![PHP Version](https://img.shields.io/packagist/php-v/phpnomad/symfony-event-dispatcher-integration.svg)](https://packagist.org/packages/phpnomad/symfony-event-dispatcher-integration)
[![License](https://img.shields.io/packagist/l/phpnomad/symfony-event-dispatcher-integration.svg)](https://packagist.org/packages/phpnomad/symfony-event-dispatcher-integration)

Integrates Symfony's EventDispatcher component with PHPNomad's event abstraction. It provides concrete `EventStrategy` and `ActionBindingStrategy` implementations that satisfy the interfaces defined in `phpnomad/event`, so application code that dispatches or listens for events keeps depending on the PHPNomad contracts while the underlying work goes through Symfony's dispatcher. This is the recommended implementation for PHPNomad applications that are not running inside a host platform with its own event system.

## Installation

```bash
composer require phpnomad/symfony-event-dispatcher-integration
```

Composer pulls in `phpnomad/event`, `phpnomad/di`, `phpnomad/loader`, and `symfony/event-dispatcher` transitively.

## What This Provides

- An `EventStrategy` implementation that wraps each PHPNomad event in a Symfony-compatible event object and dispatches it through `EventDispatcherInterface`. `attach()` supports priorities, and listener callables receive the original PHPNomad event rather than the Symfony wrapper.
- An `ActionBindingStrategy` implementation that binds an arbitrary action name to a PHPNomad event class, with an optional transformer callable for shaping the event instance from the action arguments.
- An `Initializer` class that registers both strategies with the DI container and binds Symfony's `EventDispatcher` to `EventDispatcherInterface`.

## Requirements

- `phpnomad/event` for the interfaces being implemented
- `phpnomad/di` and `phpnomad/loader` for container wiring
- `symfony/event-dispatcher` ^7.1 for the underlying dispatcher

All four are declared as direct dependencies, so Composer installs them automatically.

## Usage

Add the integration's `Initializer` to your PHPNomad bootstrapper alongside your application initializers. The loader picks up its class definitions and binds both strategies to their PHPNomad interfaces.

```php
<?php

use MyApp\MyAppInitializer;
use PHPNomad\Di\Container\Container;
use PHPNomad\Loader\Bootstrapper;
use PHPNomad\Symfony\Component\EventDispatcherIntegration\Initializer as SymfonyEventDispatcherInitializer;

$container = new Container();
$bootstrapper = new Bootstrapper(
    $container,
    new MyAppInitializer(),
    new SymfonyEventDispatcherInitializer(),
);
$bootstrapper->load();
```

Once the bootstrapper runs, any service that depends on `PHPNomad\Events\Interfaces\EventStrategy` or `PHPNomad\Events\Interfaces\ActionBindingStrategy` resolves to the Symfony-backed implementation. Your domain code continues to depend on the PHPNomad interfaces and does not reference Symfony directly.

## Documentation

Full PHPNomad documentation lives at [phpnomad.com](https://phpnomad.com). For the underlying dispatcher, see the [Symfony EventDispatcher component](https://symfony.com/doc/current/components/event_dispatcher.html).

## License

Released under the [MIT License](LICENSE.txt).
