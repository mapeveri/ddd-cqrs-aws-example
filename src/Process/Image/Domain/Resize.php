<?php

declare(strict_types=1);

namespace App\Process\Image\Domain;

interface Resize
{
    public function process(string $imageFileContent, int $width, int $height): string;
}