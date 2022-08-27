<?php

declare(strict_types=1);

namespace App\Tests\Process\Image\Application\Command\UploadImage;

use App\Process\Image\Application\Command\UploadImage\UploadImageCommandHandler;
use App\Tests\Process\Image\Domain\Event\ImageCreatedEventMother;
use App\Tests\Process\Image\Domain\ImageMother;
use App\Tests\Process\Image\ImageUnitTestCase;
use App\Tests\Shared\Utils\Faker\Faker;

final class UploadImageCommandHandlerTest extends ImageUnitTestCase
{
    private UploadImageCommandHandler $SUT;

    public function setUp(): void
    {
        $this->SUT = new UploadImageCommandHandler(
            $this->uuidGenerator(),
            $this->fileManager(),
            $this->repository(),
            $this->eventBus(),
        );

        parent::setUp();
    }

    public function testCreateImage()
    {
        $image = ImageMother::create();
        $command = UploadImageCommandMother::create(['fileName' => $image->originalFilePath(), 'content' => Faker::random()->text]);

        $this->shouldWriteFile($command->fileName(), $command->content());

        $this->shouldGenerateUuid($image->id()->value());
        $this->shouldSave($image);

        $this->shouldPublishDomainEvent(ImageCreatedEventMother::create(['id' => $image->id()->value()]));

        $this->dispatch($command, $this->SUT);
    }
}