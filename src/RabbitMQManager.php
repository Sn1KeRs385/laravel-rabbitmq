<?php

declare(strict_types=1);

namespace Sn1KeRs\LaravelRabbitmq;

use Illuminate\Contracts\Foundation\Application;
use InvalidArgumentException;
use Sn1KeRs\LaravelRabbitmq\Config\ConnectionItemConfig;
use Sn1KeRs\LaravelRabbitmq\Config\RabbitMQConfig;

/**
 * @mixin RabbitMQConnection
 */
class RabbitMQManager
{
    protected Application $app;
    protected RabbitMQConfig $config;

    /**
     * @var RabbitMQConnection[]
     */
    protected array $connections;

    public function __construct(Application $app, RabbitMQConfig $config)
    {
        $this->app = $app;
        $this->config = $config;
        $this->connections = [];
    }

    public function connection(?string $name = null): RabbitMQConnection
    {
        $name = $name ?: 'default';

        if (isset($this->connections[$name])) {
            return $this->connections[$name];
        }

        return $this->connections[$name] = $this->resolve($name);
    }

    public function resolve(?string $name = null): RabbitMQConnection
    {
        $name = $name ?: 'default';

        /** @var ConnectionItemConfig|null $connectionSettings */
        $connectionSettings = $this->config->connections[$name];

        if (!$connectionSettings) {
            throw new InvalidArgumentException("RabbitMQ connection [{$name}] not configured.");
        }

        return new RabbitMQConnection(
            $connectionSettings->host,
            $connectionSettings->port,
            $connectionSettings->user,
            $connectionSettings->password,
            $connectionSettings->virtualHost
        );
    }

    /**
     * @return array<string, RabbitMQConnection>
     */
    public function connections(): array
    {
        return $this->connections;
    }

    public function purge(?string $name = null): void
    {
        $name = $name ?: 'default';

        unset($this->connections[$name]);
    }

    /**
     * @param array<mixed> $parameters
     */
    public function __call(string $method, array $parameters): mixed
    {
        return $this->connection()->{$method}(...$parameters);
    }
}
