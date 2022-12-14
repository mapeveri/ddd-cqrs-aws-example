<?php

declare(strict_types=1);

namespace App\Shared\Domain\Bus\Event;

interface EventHandler
{
    public static function subscribedTo(): array;
}
