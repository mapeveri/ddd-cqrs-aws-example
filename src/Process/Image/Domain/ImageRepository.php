<?php

declare(strict_types=1);

namespace App\Process\Image\Domain;

use App\Process\Image\Domain\ValueObjects\ImageId;

interface ImageRepository
{
    public function find(ImageId $id): ?Image;

    public function save(Image $image): void;
}