<?php

declare(strict_types=1);

namespace App\Process\Image\Application\Query\PreviewImage;

use App\Process\Image\Application\Query\Response\ImageContentResponse;
use App\Process\Image\Domain\ImageRepository;
use App\Process\Image\Domain\Services\ImageFinder;
use App\Shared\Domain\Bus\Query\QueryHandler;
use App\Shared\Domain\FileManagerInterface;

final class PreviewImageQueryHandler implements QueryHandler
{
    private ImageFinder $finder;

    public function __construct(private FileManagerInterface $fileManager, ImageRepository $repository)
    {
        $this->finder = new ImageFinder($repository);
    }

    public function __invoke(PreviewImageQuery $query): ImageContentResponse
    {
        $image = $this->finder->__invoke($query->id());

        if (!$image->fileNameImageExists($query->imageName())) {
            throw new \RuntimeException('Image is not registered');
        }

        $contentImage = $this->fileManager->read($query->imageName());

        return new ImageContentResponse($contentImage);
    }
}