<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Service\File;

use App\Shared\Domain\FileManagerInterface;

class S3ReadFile
{
    public function __construct(private FileManagerInterface $fileManager)
    {
    }

    public function invoke(string $filename): string
    {
        return $this->fileManager->read($filename);
    }
}