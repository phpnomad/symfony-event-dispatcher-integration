<?php

namespace PHPNomad\Symfony\Component\EventDispatcherIntegration\Strategies;

use PHPNomad\Events\Interfaces\ActionBindingStrategy as ActionBindingStrategyInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use PHPNomad\Symfony\Component\EventDispatcherIntegration\Models\EventWrapper;

/**
 * ActionBindingStrategy implementation for service-based applications.
 * Uses Symfony's EventDispatcher for binding and dispatching actions.
 */
class ActionBindingStrategy implements ActionBindingStrategyInterface
{
    public function __construct(protected EventDispatcherInterface $dispatcher)
    {
    }

    /**
     * Bind an action to an event class, with optional transformation.
     *
     * @param string        $eventClass     The event class.
     * @param string        $actionToBind   The action to bind to.
     * @param callable|null $transformer    Optional transformer function.
     */
    public function bindAction(string $eventClass, string $actionToBind, ?callable $transformer = null)
    {
        $this->dispatcher->addListener($actionToBind, function (...$args) use ($transformer, $eventClass) {
            $eventInstance = $transformer ? $transformer(...$args) : new $eventClass(...$args);

            if ($eventInstance) {
                $wrappedEvent = new EventWrapper($eventInstance);
                $this->dispatcher->dispatch($wrappedEvent, $eventInstance::getId());
            }
        });
    }
}
