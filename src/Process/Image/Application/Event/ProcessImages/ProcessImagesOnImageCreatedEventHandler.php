<?php

declare(strict_types=1);

namespace App\Process\Image\Application\Event\ProcessImages;

use App\Process\Image\Domain\Event\ImageCreatedEvent;
use App\Process\Image\Domain\ImageRepository;
use App\Process\Image\Domain\Resize;
use App\Process\Image\Domain\ValueObjects\ImageId;
use App\Shared\Domain\Bus\Event\EventHandler;
use App\Shared\Domain\FileManagerInterface;
use Psr\Log\LoggerInterface;

final class ProcessImagesOnImageCreatedEventHandler implements EventHandler
{
    private const RESIZE_WIDTH = 150;
    private const RESIZE_HEIGHT = 150;

    public function __construct(
        private ImageRepository $repository,
        private LoggerInterface $logger,
        private Resize $resizeImage,
        private FileManagerInterface $fileManager
    ) {
    }

    public function __invoke(ImageCreatedEvent $event): void
    {
        $image = $this->repository->find(new ImageId($event->aggregateId()));
        if (null === $image) {
            return;
        }

        $this->logger->info(sprintf('Image processing: %s', $image->originalFilePath()));

        $imageFile = $this->fileManager->read($image->originalFilePath());

        $imageResized = $this->resizeImage->process($imageFile, self::RESIZE_WIDTH, self::RESIZE_HEIGHT);

        $this->fileManager->write(
            sprintf('%s_%s_%s', self::RESIZE_WIDTH, self::RESIZE_HEIGHT, $image->originalFilePath()),
            $imageResized
        );
    }

    public static function subscribedTo(): array
    {
        return [
            ImageCreatedEvent::class,
        ];
    }
}