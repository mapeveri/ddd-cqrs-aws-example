<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObjects;

class Uuid
{
    final public function __construct(
        private string $id
    ) {
    }

    public static function create(string $uuid): static
    {
        if (!\Ramsey\Uuid\Nonstandard\Uuid::isValid($uuid)) {
            throw new \RuntimeException('Invalid UUID');
        }

        return new static($uuid);
    }

    public static function next(): string
    {
        return \Ramsey\Uuid\Nonstandard\Uuid::uuid4()->toString();
    }

    public function __toString(): string
    {
        return $this->id;
    }

    public function equals(self $uuid): bool
    {
        return $this->id === $uuid->id;
    }

    public function value(): string
    {
        return $this->id;
    }
}