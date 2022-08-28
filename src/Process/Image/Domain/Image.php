<?php

declare(strict_types=1);

namespace App\Process\Image\Domain;

use App\Process\Image\Domain\Event\ImageCreatedEvent;
use App\Process\Image\Domain\ValueObjects\ImageId;
use App\Shared\Domain\Aggregate\AggregateRoot;

class Image extends AggregateRoot
{
    public function __construct(private ImageId $id, private string $originalFilePath, private array $processedFilesChild)
    {
    }

    public function id(): ImageId
    {
        return $this->id;
    }

    public function originalFilePath(): string
    {
        return $this->originalFilePath;
    }

    public function processedFilesChild(): array
    {
        return $this->processedFilesChild;
    }

    public static function create(ImageId $id, string $originalFilePath): self
    {
        $image = new self($id, $originalFilePath, []);

        $image->record(new ImageCreatedEvent($image->id()->value()));

        return $image;
    }

    public function addProcessedFileChild(string $fileName): void
    {
        $this->processedFilesChild[] = $fileName;
    }

    public function fileNameImageExists(string $fileName): bool
    {
        if ($fileName === $this->originalFilePath) {
            return true;
        }

        if (isset($this->processedFilesChild[0]) && in_array($fileName, $this->processedFilesChild[0])) {
            return true;
        }

        return false;
    }
}