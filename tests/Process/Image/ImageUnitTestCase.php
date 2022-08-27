<?php

declare(strict_types=1);

namespace App\Tests\Process\Image;

use App\Process\Image\Domain\Image;
use App\Process\Image\Domain\ImageRepository;
use App\Process\Image\Domain\Resize;
use App\Tests\Shared\Infrastructure\PhpUnit\UnitTestCase;
use Mockery\MockInterface;

class ImageUnitTestCase extends UnitTestCase
{
    protected ImageRepository|MockInterface $repository;
    protected Resize|MockInterface $resizeImageService;

    protected function shouldFind(Image $image): void
    {
        $this->repository()
            ->shouldReceive('find')
            ->with($this->similarTo($image->id()))
            ->andReturn($image);
    }

    protected function shouldSave(Image $image): void
    {
        $this->repository()
            ->shouldReceive('save')
            ->with(\Mockery::on(function ($receivedObject) use ($image): bool {
                return $image->id()->value() === $receivedObject->id()->value() &&
                    $image->originalFilePath() === $receivedObject->originalFilePath() &&
                    $image->processedFilesChild() === $receivedObject->processedFilesChild();
            }))
            ->andReturnNull();
    }

    protected function shouldResizeImage(string $imageFileContent, string $imageFileContentResized): void
    {
        $this->resizeImageService()
            ->shouldReceive('process')
            ->with(
                $imageFileContent,
                150,
                150
            )
            ->andReturn($imageFileContentResized);
    }

    protected function repository(): ImageRepository|MockInterface
    {
        return $this->repository = $this->repository ?? $this->mock(ImageRepository::class);
    }

    protected function resizeImageService(): Resize|MockInterface
    {
        return $this->resizeImageService = $this->resizeImageService ?? $this->mock(Resize::class);
    }
}