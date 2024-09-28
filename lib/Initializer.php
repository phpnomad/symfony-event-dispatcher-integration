<?php

namespace PHPNomad\Symfony\Component\EventDispatcherIntegration;

use PHPNomad\Di\Interfaces\CanSetContainer;
use PHPNomad\Di\Traits\HasSettableContainer;
use PHPNomad\Events\Interfaces\ActionBindingStrategy;
use PHPNomad\Events\Interfaces\EventStrategy;
use PHPNomad\Loader\Interfaces\HasClassDefinitions;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class Initializer implements HasClassDefinitions, CanSetContainer
{
    use HasSettableContainer;

    public function getClassDefinitions(): array
    {
        return [
            Strategies\EventStrategy::class => EventStrategy::class,
            Strategies\ActionBindingStrategy::class => ActionBindingStrategy::class,
            EventDispatcher::class => EventDispatcherInterface::class,
        ];
    }
}