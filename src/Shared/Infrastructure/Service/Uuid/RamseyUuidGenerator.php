<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Service\Uuid;

use App\Shared\Domain\UuidGenerator;
use App\Shared\Domain\ValueObjects\Uuid;

final class RamseyUuidGenerator implements UuidGenerator
{
    public function generate(): string
    {
        return Uuid::next();
    }
}