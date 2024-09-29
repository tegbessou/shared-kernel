# Shared Kernel

[![CI](https://github.com/tegbessou/shared-kernel/actions/workflows/ci.yml/badge.svg)](https://github.com/tegbessou/shared-kernel/actions/workflows/ci.yml)

A shared kernel for Domain-Driven Design (DDD) projects in PHP,
providing common abstractions and utilities to facilitate building DDD applications with CQRS and hexagonal architecture

## Features

- Domain events and event handling mechanisms
- Repository interfaces
- Utilities for domain modeling
- Setup of command and query buses via Symfony Messenger
- Api Platform pagination integration

## Installation

Install the package via Composer:

```bash
composer require tegbessou/shared-kernel
```

## Domain Events

Domain events are a way to communicate changes in the domain model to other parts of the application. They are used to decouple the domain model from the rest of the application and to enable event sourcing.

### Defining Domain Events

Domain events are simple PHP classes that extends the `DomainEvent` abstract class.
They should be immutable and contain only data that is relevant to the event.

The abstract class provides a `occurredOn` method that returns the date and time when the event occurred
and an id that can be used to uniquely identify the event.

```php
use Tegbessou\SharedKernel\Domain;

final readonly class UserRegistered extends DomainEvent
{
    public function __construct(
        public string $userId
    ) {}
}
```

### Record Domain Events

Domain events can be recorded by entities or aggregates by calling the `record` method on the `EntityDomainEventTrait` trait.

```php
use Tegbessou\SharedKernel\Domain;

final class User
{
    use EntityDomainEventTrait;

    public static function register(UserId $id): void
    {
        self::recordEvent(
            new UserRegistered(
                $id->value(),
            )
        );
    }
}
```

### Dispatch Domain Events

Domain events can be dispatched to event handlers by calling the `dispatch` method on the `DomainEventDispatcher` class.
This method will dispatch all events recorded by the entity or aggregate.

```php
use Tegbessou\SharedKernel\Domain;
use TegCorp\SharedKernelBundle\Application\EventDispatcher;

$user = User::register(new UserId('123'));

$dispatcher = new DomainEventDispatcher();

$dispatcher->dispatch($user);
```

If you use the dependency injection container, you can inject the `DomainEventDispatcherInterface` service and use it to dispatch events.

## CQRS

The shared kernel provides a simple setup for command and query buses using Symfony Messenger.

### Command
To implement commands and command handlers with autowiring in your project, you can follow these steps:  
- Define a Command: Create a simple command class.
- Create a Command Handler: Implement a handler for the command.
- Dispatch the Command: Dispatch the command to the command bus.

#### Define a Command
Create a command class that represents the action you want to perform.

```php
use Tegbessou\SharedKernel\Application\Command;

/**
 * @implements CommandInterface<UserId>
 */
final class RegisterUserCommand implements CommandInterface
{
    public function __construct(
        public string $userId
    ) {}
}
```

#### Create a Command Handler

Create a command handler class that should be tagged as a command handler with the attribute `AsCommandHandler`.

```php
use Tegbessou\SharedKernel\Application\Command;

#[AsCommandHandler]
final class RegisterUserCommandHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function __invoke(RegisterUserCommand $command): void
    {
        $user = User::create($command->userId);

        $this->userRepository->save($user);
    }
}
```

#### Dispatch the Command

Dispatch the command to the command bus from the adapter layer (controller, console...).

```php
use Tegbessou\SharedKernel\Application\Command;

final class RegisterUserController
{
    public function __construct(
        private CommandBusInterface $commandBus
    ) {}

    public function __invoke(RegisterUserCommand $command): void
    {
        $this->commandBus->dispatch($command);
    }
}
```

### Query

To implement queries and query handlers with autowiring in your project, you can follow these steps:
- Define a Query: Create a simple query class.
- Create a Query Handler: Implement a handler for the query.
- Dispatch the Query: Dispatch the query to the query bus.

#### Define a Query

Create a query class that represents the action you want to perform.

```php
use Tegbessou\SharedKernel\Application\Query;

/**
 * @implements QueryInterface<User>
 */
final class GetUserQuery implements QueryInterface
{
    public function __construct(
        public string $userId
    ) {}
}
```

#### Create a Query Handler

Create a query handler class that should be tagged as a query handler with the attribute `AsQueryHandler`.

```php
use Tegbessou\SharedKernel\Application\Query;

#[AsQueryHandler]
final class GetUserQueryHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function __invoke(GetUserQuery $query): User
    {
        return $this->userRepository->get($query->userId);
    }
}
```

#### Dispatch the Query

Dispatch the query to the query bus from the adapter layer (controller, console...).

```php
use Tegbessou\SharedKernel\Application\Query;

final class GetUserQueryController
{
    public function __construct(
        private QueryBusInterface $queryBus
    ) {}

    public function __invoke(GetUserQuery $query): User
    {
        return $this->queryBus->dispatch($query);
    }
}
```

## Transactions

In the implementation of `CommandBusInterface` the shared kernel provides start a transaction before executing the command handler and commit it after the execution.

```php
public function __construct(
    MessageBusInterface $commandBus,
    private TransactionInterface $transaction,
) {
    $this->messageBus = $commandBus;
}

/**
 * @template T
 *
 * @param CommandInterface<T> $command
 *
 * @return T
 */
#[\Override]
public function dispatch(CommandInterface $command): mixed
{
    try {
        $this->transaction->begin();

        /* @var T */
        $result = $this->handle($command);

        $this->transaction->commit();

        return $result;
    } catch (HandlerFailedException $e) {
        $this->transaction->rollback();

        if ($exception = current($e->getWrappedExceptions())) {
            throw $exception;
        }

        throw $e;
    }
}
```
