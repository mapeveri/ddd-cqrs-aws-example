<?php

declare(strict_types=1);

namespace App\Process\Image\Application\Event\ProcessImages;

use App\Process\Image\Domain\Event\ImageCreatedEvent;
use App\Process\Image\Domain\ImageRepository;
use App\Process\Image\Domain\ValueObjects\ImageId;
use App\Shared\Domain\Bus\Event\EventHandler;
use Psr\Log\LoggerInterface;

final class ProcessImagesOnImageCreatedEventHandler implements EventHandler
{
    public function __construct(private ImageRepository $repository, private LoggerInterface $logger)
    {
    }

    public function __invoke(ImageCreatedEvent $event): void
    {
        $image = $this->repository->find(new ImageId($event->aggregateId()));
        if (null === $image) {
            return;
        }

        echo $image->originalFilePath();
        $this->logger->info(sprintf('Processing: %s', $image->originalFilePath()));
    }

    public static function subscribedTo(): array
    {
        return [
            ImageCreatedEvent::class,
        ];
    }
}