<?php

declare(strict_types=1);

namespace App\Process\Image\Domain\Exceptions;

use App\Shared\Domain\DomainError;

final class ImageNotExist extends DomainError
{
    public function __construct(private string $id)
    {
        parent::__construct();
    }

    public function errorCode(): string
    {
        return 'image_not_exist';
    }

    protected function errorMessage(): string
    {
        return sprintf('The image <%s> does not exist', $this->id);
    }
}