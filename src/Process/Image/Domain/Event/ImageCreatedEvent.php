<?php

declare(strict_types=1);

namespace App\Process\Image\Domain\Event;

use App\Shared\Domain\Bus\Event\DomainEvent;

final class ImageCreatedEvent extends DomainEvent
{
    public function __construct(
        string $id,
        string $eventId = null,
        string $occurredOn = null
    ) {
        parent::__construct($id, $eventId, $occurredOn);
    }

    public static function eventName(): string
    {
        return 'image.created';
    }

    public static function eventClass(): string
    {
        return self::class;
    }

    public static function fromPrimitives(
        string $aggregateId,
        array $body,
        string $eventId,
        string $occurredOn
    ): DomainEvent {
        return new self(
            $aggregateId,
            $eventId,
            $occurredOn);
    }

    public function toPrimitives(): array
    {
        return [
            'aggregateId' => $this->aggregateId(),
        ];
    }
}