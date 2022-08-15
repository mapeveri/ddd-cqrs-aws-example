<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Symfony\Messenger\Serializer;

use App\Shared\Domain\Bus\Event\DomainEvent;
use App\Shared\Infrastructure\Symfony\Messenger\MessageMapping;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Stamp\NonSendableStampInterface;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;

final class MessageSerializer implements SerializerInterface
{
    public function __construct(private MessageMapping $mapping)
    {
    }

    public function decode(array $encodedEnvelope): Envelope
    {
        $encodedEnvelope = json_decode($encodedEnvelope['body'], true);
        $rawData = json_decode($encodedEnvelope['Message'], true);
        $headers = $encodedEnvelope['MessageAttributes']['Headers'];

        /** @var DomainEvent $class */
        $class = $this->mapping->for($rawData['type']);

        $event = $class::fromPrimitives(
            $rawData['payload']['id'],
            $rawData['payload'],
            $rawData['id'],
            $rawData['occurred_on']
        );

        $envelope = new Envelope($event);
        $envelope = $envelope->withoutStampsOfType(NonSendableStampInterface::class);

        $stamps = [];
        if (isset($headers['stamps'])) {
            $stamps = unserialize($headers['stamps']);
        }

        return $envelope->with(...$stamps);
    }

    public function encode(Envelope $envelope): array
    {
        $envelope = $envelope->withoutStampsOfType(NonSendableStampInterface::class);

        /** @var DomainEvent $event */
        $event = $envelope->getMessage();

        $allStamps = [];
        foreach ($envelope->all() as $stamps) {
            $allStamps = array_merge($allStamps, $stamps);
        }

        return [
            'body' => json_encode(
                [
                    'id' => $event->eventId(),
                    'type' => $event::eventName(),
                    'occurred_on' => $event->occurredOn(),
                    'payload' => array_merge(['id' => $event->aggregateId()], $event->toPrimitives())
                ]
            ),
            'headers' => [
                // store stamps as a header - to be read in decode()
                'stamps' => serialize($allStamps)
            ],
        ];
    }
}