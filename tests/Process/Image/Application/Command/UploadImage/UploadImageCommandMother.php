<?php

declare(strict_types=1);

namespace App\Tests\Process\Image\Application\Command\UploadImage;

use App\Process\Image\Application\Command\UploadImage\UploadImageCommand;
use App\Tests\Shared\Domain\ValueObjects\DuplicatorMother;
use App\Tests\Shared\Utils\Faker\Faker;

final class UploadImageCommandMother
{
    public static function create(?array $overrideFields = null): UploadImageCommand
    {
        $command = new UploadImageCommand(
            Faker::random()->filePath(),
            Faker::random()->text(),
        );

        if (null !== $overrideFields) {
            return DuplicatorMother::with($command, $overrideFields);
        }

        return $command;
    }
}