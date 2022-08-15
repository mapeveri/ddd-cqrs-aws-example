<?php

declare(strict_types=1);

namespace App\Process\Image\Application\Command\UploadImage;

use App\Process\Image\Domain\Image;
use App\Process\Image\Domain\ImageRepository;
use App\Process\Image\Domain\ValueObjects\ImageId;
use App\Shared\Domain\Bus\Command\CommandHandler;
use App\Shared\Domain\Bus\Event\EventBus;
use App\Shared\Domain\FileManagerInterface;

final class UploadImageCommandHandler implements CommandHandler
{
    public function __construct(
        private FileManagerInterface $fileManager,
        private ImageRepository $repository,
        private EventBus $eventBus
    ) {
    }

    public function __invoke(UploadImageCommand $command): void
    {
        $this->fileManager->write($command->fileName(), $command->content());

        $image = Image::create(ImageId::create(ImageId::next()), $command->fileName());

        $this->repository->save($image);

        $this->eventBus->publish(...$image->pullDomainEvents());
    }
}