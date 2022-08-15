<?php

declare(strict_types=1);

namespace App\Process\Image\Application\Command\UploadImage;

use App\Shared\Domain\Bus\Command\Command;

final class UploadImageCommand implements Command
{
    public function __construct(private string $fileName, private string $content)
    {
    }

    public function fileName(): string
    {
        return $this->fileName;
    }

    public function content(): string
    {
        return $this->content;
    }
}