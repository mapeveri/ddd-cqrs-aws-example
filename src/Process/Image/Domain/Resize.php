<?php

declare(strict_types=1);

namespace App\Process\Image\Domain;

interface Resize
{
    public function process(string $imageFile, int $width, int $height): string;
}