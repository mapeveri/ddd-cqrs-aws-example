<?php

declare(strict_types=1);

namespace App\Tests\Process\Image\Domain\Event;

use App\Process\Image\Domain\Event\ImageCreatedEvent;
use App\Tests\Process\Image\Domain\ValueObjects\ImageIdMother;
use App\Tests\Shared\Domain\ValueObjects\DuplicatorMother;

final class ImageCreatedEventMother
{
    public static function create(?array $overrideFields = null): ImageCreatedEvent
    {
        $event = new ImageCreatedEvent(
            ImageIdMother::create()->value(),
        );

        if (null !== $overrideFields) {
            return DuplicatorMother::with($event, $overrideFields);
        }

        return $event;
    }
}