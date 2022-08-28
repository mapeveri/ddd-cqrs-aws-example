<?php

declare(strict_types=1);

namespace App\Process\Image\Application\Query\Response;

use App\Shared\Domain\Bus\Query\Response;

final class ImageContentResponse implements Response
{
    public function __construct(private string $contentImage)
    {
    }

    public function contentImage(): string
    {
        return $this->contentImage;
    }
}