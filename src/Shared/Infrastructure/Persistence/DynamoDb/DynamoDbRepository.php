<?php

namespace App\Shared\Infrastructure\Persistence\DynamoDb;

use Aws\DynamoDb\DynamoDbClient;

abstract class DynamoDbRepository
{
    public function __construct(private DynamoDbClient $dbClient)
    {
    }

    public function client(): DynamoDbClient
    {
        return $this->dbClient;
    }
}