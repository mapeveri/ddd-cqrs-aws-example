<?php

declare(strict_types=1);

namespace App\Process\Image\Domain\Services;

use App\Process\Image\Domain\Exceptions\ImageNotExist;
use App\Process\Image\Domain\Image;
use App\Process\Image\Domain\ImageRepository;
use App\Process\Image\Domain\ValueObjects\ImageId;

final class ImageFinder
{
    public function __construct(private ImageRepository $repository)
    {
    }

    public function __invoke(string $id): Image
    {
        $image = $this->repository->find(new ImageId($id));
        if (null === $image) {
            throw new ImageNotExist($id);
        }

        return $image;
    }
}