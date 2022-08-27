<?php

declare(strict_types=1);

namespace App\Tests\Shared\Infrastructure\PhpUnit;

use App\Shared\Domain\Bus\Command\Command;
use App\Shared\Domain\Bus\Command\CommandBus;
use App\Shared\Domain\Bus\Command\CommandHandler;
use App\Shared\Domain\Bus\Event\DomainEvent;
use App\Shared\Domain\Bus\Event\EventBus;
use App\Shared\Domain\Bus\Query\Query;
use App\Shared\Domain\Bus\Query\QueryBus;
use App\Shared\Domain\Bus\Query\QueryHandler;
use App\Shared\Domain\Bus\Query\Response;
use App\Shared\Domain\FileManagerInterface;
use App\Shared\Domain\UuidGenerator;
use App\Tests\Shared\Domain\TestUtils;
use Exception;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Mockery\Matcher\MatcherAbstract;
use Mockery\MockInterface;

use function Lambdish\Phunctional\map;

abstract class UnitTestCase extends MockeryTestCase
{
    private EventBus|MockInterface|null $eventBus;
    private QueryBus|MockInterface $queryBus;
    private CommandBus|MockInterface $commandBus;
    private UuidGenerator|MockInterface $uuidGenerator;
    private FileManagerInterface|MockInterface $fileManager;

    protected function mock(string $className): MockInterface
    {
        return Mockery::mock($className);
    }

    protected function shouldPublishDomainEvent(DomainEvent $domainEvent): void
    {
        $this->shouldPublishDomainEvents($domainEvent);
    }

    protected function shouldPublishDomainEvents(DomainEvent ...$domainEvent): void
    {
        $this->eventBus()
            ->shouldReceive('publish')
            ->with(...map(fn(DomainEvent $event) => $this->similarTo($event), $domainEvent))
            ->andReturnNull();
    }

    protected function shouldDispatchQuery(Query $query, mixed $result = null): void
    {
        $this->queryBus()
            ->shouldReceive('dispatch')
            ->with($this->equalTo($query))
            ->andReturn($result);
    }

    protected function shouldDispatchCommand(Command $command): void
    {
        $this->commandBus()
            ->shouldReceive('dispatch')
            ->with($this->similarTo($command));
    }

    protected function shouldNotPublishDomainEvent(): void
    {
        $this->eventBus()
            ->shouldReceive('dispatch')
            ->withNoArgs()
            ->andReturnNull();
    }

    protected function shouldGenerateUuid(string $uuid): void
    {
        $this->uuidGenerator()
            ->shouldReceive('generate')
            ->once()
            ->withNoArgs()
            ->andReturn($uuid);
    }

    protected function shouldReadFile(string $file, string $content): void
    {
        $this->fileManager()
            ->shouldReceive('read')
            ->once()
            ->with($file)
            ->andReturn($content);
    }

    protected function shouldWriteFile(string $file, string $content): void
    {
        $this->fileManager()
            ->shouldReceive('write')
            ->once()
            ->with(
                $file,
                $content
            )
            ->andReturn($content);
    }

    protected function eventBus(): EventBus|MockInterface
    {
        return $this->eventBus = $this->eventBus ?? $this->mock(EventBus::class);
    }

    protected function commandBus(): CommandBus|MockInterface
    {
        return $this->commandBus = $this->commandBus ?? $this->mock(CommandBus::class);
    }

    protected function uuidGenerator(): UuidGenerator|MockInterface
    {
        return $this->uuidGenerator = $this->uuidGenerator ?? $this->mock(UuidGenerator::class);
    }

    protected function queryBus(): QueryBus|MockInterface
    {
        return $this->queryBus = $this->queryBus ?? $this->mock(QueryBus::class);
    }

    protected function fileManager(): FileManagerInterface|MockInterface
    {
        return $this->fileManager = $this->fileManager ?? $this->mock(FileManagerInterface::class);
    }

    protected function dispatch(Command $command, callable $commandHandler): void
    {
        $this->instanceOf($commandHandler, CommandHandler::class);
        $commandHandler($command);
    }

    protected function assertAskResponse(Response $expected, Query $query, callable $queryHandler): void
    {
        $this->instanceOf($queryHandler, QueryHandler::class);
        $actual = $queryHandler($query);

        $this->assertEquals($expected, $actual);
    }

    protected function assertAsk(mixed $expected, Query $query, callable $queryHandler): void
    {
        $this->instanceOf($queryHandler, QueryHandler::class);
        $actual = $queryHandler($query);

        $this->assertEquals($expected, $actual);
    }

    protected function assertAskThrowsException(string $expectedErrorClass, Query $query, callable $queryHandler): void
    {
        $this->expectException($expectedErrorClass);

        $this->instanceOf($queryHandler, QueryHandler::class);
        $queryHandler($query);
    }

    protected function similarTo($value, $delta = 0.0): MatcherAbstract
    {
        return TestUtils::similarTo($value, $delta);
    }

    protected function instanceOf(mixed $object, string $instance): void
    {
        if (!$object instanceof $instance) {
            throw new Exception(sprintf('Not instance of %s', $instance));
        }
    }
}