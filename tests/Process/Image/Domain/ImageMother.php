<?php

declare(strict_types=1);

namespace App\Tests\Process\Image\Domain;

use App\Process\Image\Domain\Image;
use App\Tests\Process\Image\Domain\ValueObjects\ImageIdMother;
use App\Tests\Shared\Domain\ValueObjects\DuplicatorMother;
use App\Tests\Shared\Utils\Faker\Faker;

final class ImageMother
{
    public static function create(?array $overrideFields = null): Image
    {
        $image = new Image(
            id: ImageIdMother::create(),
            originalFilePath: Faker::random()->filePath(),
            processedFilesChild: [],
        );

        if (null !== $overrideFields) {
            return DuplicatorMother::with($image, $overrideFields);
        }

        return $image;
    }
}