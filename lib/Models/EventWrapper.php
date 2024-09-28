<?php

namespace PHPNomad\Symfony\Component\EventDispatcherIntegration\Models;


use PHPNomad\Events\Interfaces\Event as CustomEventInterface;
use Symfony\Contracts\EventDispatcher\Event as SymfonyEvent;

/**
 * EventWrapper class to adapt a PHPNomad custom event to be compatible with Symfony's Event system.
 */
class EventWrapper extends SymfonyEvent
{
    /**
     * EventWrapper constructor.
     *
     * @param CustomEventInterface $originalEvent The original event instance.
     */
    public function __construct(public readonly CustomEventInterface $originalEvent)
    {
    }
}