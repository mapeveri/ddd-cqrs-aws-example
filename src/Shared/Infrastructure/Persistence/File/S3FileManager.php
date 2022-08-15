<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Persistence\File;

use App\Shared\Domain\FileManagerInterface;
use Aws\S3\S3ClientInterface;

final class S3FileManager implements FileManagerInterface
{
    public const SEPARATOR = 'amazonaws.com/';

    public function __construct(
        private S3ClientInterface $client,
        private string $bucket
    ) {
    }

    public function write(string $filename, string $content): string
    {
        $result = $this->client->putObject([
            'Bucket' => $this->bucket,
            'Key' => $this->sanitizeFileName($filename),
            'Body' => $content
        ]);

        return $result->toArray()['ObjectURL'];
    }

    public function read(string $filename): string
    {
        /** @phpstan-ignore-next-line */
        $aws = $this->client->getObject([
            'Bucket' => $this->bucket,
            'Key' => urldecode($this->sanitizeFileName($filename))
        ]);

        return (string)$aws->toArray()['Body'];
    }

    public function listObjects(string $prefix): mixed
    {
        return $this->client->getIterator('ListObjects', [
            'Bucket' => $this->bucket,
            'Prefix' => $prefix
        ]);
    }

    private function sanitizeFileName(string $filename): string
    {
        if (!str_contains($filename, self::SEPARATOR)) {
            return $filename;
        }

        $parts = explode(self::SEPARATOR, $filename);

        /** @var string $key */
        $key = end($parts);

        return $key;
    }
}