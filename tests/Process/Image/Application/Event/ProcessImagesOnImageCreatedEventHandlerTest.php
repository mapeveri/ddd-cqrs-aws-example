<?php

declare(strict_types=1);

namespace App\Tests\Process\Image\Application\Event;

use App\Process\Image\Application\Event\ProcessImages\ProcessImagesOnImageCreatedEventHandler;
use App\Tests\Process\Image\Domain\Event\ImageCreatedEventMother;
use App\Tests\Process\Image\Domain\ImageMother;
use App\Tests\Process\Image\Domain\ValueObjects\ImageIdMother;
use App\Tests\Process\Image\ImageUnitTestCase;
use App\Tests\Shared\Utils\Faker\Faker;

use function Lambdish\Phunctional\apply;

final class ProcessImagesOnImageCreatedEventHandlerTest extends ImageUnitTestCase
{
    private ProcessImagesOnImageCreatedEventHandler $SUT;

    public function setUp(): void
    {
        $this->SUT = new ProcessImagesOnImageCreatedEventHandler(
            $this->repository(),
            $this->resizeImageService(),
            $this->fileManager()
        );

        parent::setUp();
    }

    public function testResizeImage()
    {
        $event = ImageCreatedEventMother::create();
        $filePath = Faker::random()->filePath();
        $resizedFilePath = sprintf('%s_%s_%s', 150, 150, $filePath);

        $image = ImageMother::create([
            'id' => ImageIdMother::create($event->aggregateId()),
            'originalFilePath' => $filePath,
            'processedFilesChild' => [$resizedFilePath]
        ]);

        $this->shouldFind($image);

        $contentImageFile = Faker::random()->text();
        $imageFileContentResized = Faker::random()->text();
        $this->shouldReadFile($image->originalFilePath(), $contentImageFile);
        $this->shouldResizeImage($contentImageFile, $imageFileContentResized);

        $this->shouldWriteFile($resizedFilePath, $imageFileContentResized);
        $this->shouldSave($image);

        apply( $this->SUT, [$event]);
    }
}