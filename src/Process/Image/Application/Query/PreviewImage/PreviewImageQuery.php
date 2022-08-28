<?php

declare(strict_types=1);

namespace App\Process\Image\Application\Query\PreviewImage;

use App\Shared\Domain\Bus\Query\Query;

final class PreviewImageQuery implements Query
{
    public function __construct(private string $id, private string $imageName)
    {
    }

    public function id(): string
    {
        return $this->id;
    }

    public function imageName(): string
    {
        return $this->imageName;
    }

}