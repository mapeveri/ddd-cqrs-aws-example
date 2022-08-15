<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Service\File;

use App\Shared\Domain\FileManagerInterface;

class S3WriteFile
{
    public function __construct(private FileManagerInterface $fileManager)
    {
    }

    public function invoke(string $fileName, string $content): string
    {
        return $this->fileManager->write($fileName, $content);
    }
}