<?php

declare(strict_types=1);

namespace App\Process\Image\Infrastructure\Service;

use App\Process\Image\Domain\Resize;

final class ResizeImage implements Resize
{
    public function process(string $imageFileContent, int $width, int $height): string
    {
        $newTrueColorImage = imagecreatetruecolor($width, $height);
        $newImage = imagecreatefromstring($imageFileContent);

        imagecopyresampled(
            $newTrueColorImage,
            $newImage,
            0,
            0,
            0,
            0,
            $width,
            $height,
            imagesx($newImage),
            imagesy($newImage)
        );

        $newFile = tempnam("/tmp", "FOO");
        imagepng($newTrueColorImage, $newFile, 9);

        $content = file_get_contents($newFile);
        unlink($newFile);
        imagedestroy($newImage);

        return $content;
    }
}