<?php

declare(strict_types=1);

namespace Drupal\events\Event;

use Symfony\Contracts\EventDispatcher\Event;

/**
 * @template T
 */
final class DataEvent extends Event
{
    public const string EVENT_NAME = 'event_name';

    /**
     * @param T $data
     */
    public function __construct(
        private readonly mixed $data,
    ) {
    }

    /**
     * @return T
     */
    public function getData()
    {
        return $this->data;
    }
}
