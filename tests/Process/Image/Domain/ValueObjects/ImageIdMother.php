<?php

declare(strict_types=1);

namespace App\Tests\Process\Image\Domain\ValueObjects;

use App\Process\Image\Domain\ValueObjects\ImageId;

final class ImageIdMother
{
    public static function create(?string $id = null): ImageId
    {
        return null === $id ? ImageId::create(ImageId::next()) : ImageId::create($id);
    }
}