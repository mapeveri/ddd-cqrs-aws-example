<?php

declare(strict_types=1);

namespace App\Process\Image\Infrastructure\Persistence\DynamoDb;

use App\Process\Image\Domain\Image;
use App\Process\Image\Domain\ImageRepository;
use App\Process\Image\Domain\ValueObjects\ImageId;
use App\Shared\Infrastructure\Persistence\DynamoDb\DynamoDbRepository;
use Aws\DynamoDb\Marshaler;

final class DynamoDbImageRepository extends DynamoDbRepository implements ImageRepository
{
    private const TABLE = 'images';

    public function find(ImageId $id): ?Image
    {
        $image = $this->client()->getItem([
            'ConsistentRead' => true,
            'TableName' => self::TABLE,
            'Key' => [
                'id' => ['S' => $id->value()],
            ]
        ]);

        if (null === $image) {
            return null;
        }

        return new Image(
            $id,
            $image['Item']['originalFilePath']['S'],
            $image['Item']['processedFilesChild']['L']
        );
    }

    public function save(Image $image): void
    {
        $m = new Marshaler();
        $this->client()->putItem([
            'TableName' => self::TABLE,
            'Item' => $m->marshalItem([
                'id' => $image->id()->value(),
                'originalFilePath' => $image->originalFilePath(),
                'processedFilesChild' => $image->processedFilesChild(),
            ])
        ]);
    }
}