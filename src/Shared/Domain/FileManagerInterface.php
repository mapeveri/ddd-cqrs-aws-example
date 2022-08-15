<?php

declare(strict_types=1);

namespace App\Shared\Domain;

interface FileManagerInterface
{
    public function write(string $filename, string $content): string;

    public function read(string $filename): string;
}