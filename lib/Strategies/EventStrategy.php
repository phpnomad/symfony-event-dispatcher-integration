<?php

namespace PHPNomad\Symfony\Component\EventDispatcherIntegration\Strategies;

use PHPNomad\Events\Interfaces\EventStrategy as EventStrategyInterface;
use PHPNomad\Symfony\Component\EventDispatcherIntegration\Models\EventWrapper;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use PHPNomad\Events\Interfaces\Event as CustomEventInterface;

/**
 * EventStrategy implementation for handling event broadcasting and listeners.
 * Uses Symfony's EventDispatcher for attaching, detaching, and broadcasting events.
 */
class EventStrategy implements EventStrategyInterface
{
    public function __construct(protected EventDispatcherInterface $dispatcher)
    {
    }

    /**
     * Broadcast an event to all registered listeners.
     *
     * @param CustomEventInterface $event The event to broadcast.
     */
    public function broadcast(CustomEventInterface $event): void
    {
        $wrappedEvent = new EventWrapper($event);
        $this->dispatcher->dispatch($wrappedEvent, $event::class);
    }

    /**
     * Attach a listener to an event.
     *
     * @param string     $event    The event name.
     * @param callable   $action   The listener callback.
     * @param int|null   $priority Optional listener priority.
     */
    public function attach(string $event, callable $action, ?int $priority = null): void
    {
        $wrappedAction = $this->wrapAction($action);

        $this->dispatcher->addListener($event, $wrappedAction, $priority ?? 0);
    }

    /**
     * Detach a listener from an event.
     *
     * @param string     $event    The event name.
     * @param callable   $action   The listener callback.
     * @param int|null   $priority Optional listener priority.
     */
    public function detach(string $event, callable $action, ?int $priority = null): void
    {
        $wrappedAction = $this->wrapAction($action);
        $this->dispatcher->removeListener($event, $wrappedAction);
    }

    /**
     * Wraps the original action to ensure the listener receives the original event.
     *
     * @param callable $action The original action callback.
     *
     * @return callable The wrapped action callback.
     */
    private function wrapAction(callable $action): callable
    {
        return function (EventWrapper $wrappedEvent) use ($action) {
            $action($wrappedEvent->originalEvent);
        };
    }
}
