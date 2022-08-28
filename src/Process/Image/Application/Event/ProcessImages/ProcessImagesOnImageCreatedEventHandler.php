<?php

declare(strict_types=1);

namespace App\Process\Image\Application\Event\ProcessImages;

use App\Process\Image\Domain\Event\ImageCreatedEvent;
use App\Process\Image\Domain\ImageRepository;
use App\Process\Image\Domain\Resize;
use App\Process\Image\Domain\Services\ImageFinder;
use App\Shared\Domain\Bus\Event\EventHandler;
use App\Shared\Domain\FileManagerInterface;

final class ProcessImagesOnImageCreatedEventHandler implements EventHandler
{
    private const RESIZE_WIDTH = 150;
    private const RESIZE_HEIGHT = 150;

    private ImageFinder $finder;

    public function __construct(
        private ImageRepository $repository,
        private Resize $resizeImage,
        private FileManagerInterface $fileManager
    ) {
        $this->finder = new ImageFinder($this->repository);
    }

    public function __invoke(ImageCreatedEvent $event): void
    {
        $image = $this->finder->__invoke($event->aggregateId());

        $imageFileContent = $this->fileManager->read($image->originalFilePath());

        $imageResized = $this->resizeImage->process(
            $imageFileContent,
            self::RESIZE_WIDTH,
            self::RESIZE_HEIGHT
        );

        $resizedFileName = sprintf(
            '%s_%s_%s',
            self::RESIZE_WIDTH,
            self::RESIZE_HEIGHT,
            $image->originalFilePath()
        );
        $this->fileManager->write($resizedFileName, $imageResized);

        $image->addProcessedFileChild($resizedFileName);
        $this->repository->save($image);
    }

    public static function subscribedTo(): array
    {
        return [
            ImageCreatedEvent::class,
        ];
    }
}