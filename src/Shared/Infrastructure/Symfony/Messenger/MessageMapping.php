<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Symfony\Messenger;

use App\Shared\Domain\Bus\Event\EventHandler;
use RuntimeException;

final class MessageMapping
{
    private mixed $mapping;

    public function __construct(iterable $mapping)
    {
        $this->mapping = $this->getAsyncMapping($mapping);
    }

    public function for(string $name): string
    {
        if (!isset($this->mapping[$name])) {
            throw new RuntimeException("The EventDomain Event Class for <$name> doesn't exists or have no subscribers");
        }

        return $this->mapping[$name];
    }

    private function getAsyncMapping(iterable $handlers): array
    {
        $result = [];

        foreach ($handlers as $handler) {
            if ($handler instanceof EventHandler) {
                $events = $handler::subscribedTo();
                foreach ($events as $event) {
                    $result[$event::eventName()] = $event;
                }
            }
        }

        return $result;
    }
}