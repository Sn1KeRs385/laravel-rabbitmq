<?php

declare(strict_types=1);

namespace Sn1KeRs\LaravelRabbitmq\Broadcast;

use Illuminate\Contracts\Broadcasting\Broadcaster;
use Illuminate\Contracts\Foundation\Application;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Sn1KeRs\LaravelRabbitmq\Config\BroadcastingChannelConfig;
use Sn1KeRs\LaravelRabbitmq\RabbitMQManager;

/**
 * @mixin AMQPStreamConnection
 */
class RabbitMQBroadcaster implements Broadcaster
{
    protected Application $app;
    protected RabbitMQManager $rabbitMQManager;
    /**
     * @var BroadcastingChannelConfig[]
     */
    protected array $config;
    protected string $connection;

    /**
     * @param BroadcastingChannelConfig[] $config
     */
    public function __construct(Application $app, RabbitMQManager $rabbitMQManager, array $config, string $connection)
    {
        $this->app = $app;
        $this->rabbitMQManager = $rabbitMQManager;
        $this->config = $config;
        $this->connection = $connection;
    }

    /**
     * Authenticate the incoming request for a given channel.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function auth($request)
    {
        return true;
    }

    /**
     * Return the valid authentication response.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function validAuthenticationResponse($request, $result)
    {
        return true;
    }

    /**
     * Broadcast the given event.
     *
     * @param string[]            $channels
     * @param string              $event
     * @param array<mixed, mixed> $payload
     *
     * @throws \Illuminate\Broadcasting\BroadcastException
     */
    public function broadcast(array $channels, $event, array $payload = []): void
    {
        $messageParser = new MessageParser($event, $payload);
        $defaultBroadcastingChannelConfig = new BroadcastingChannelConfig();

        foreach ($channels as $channel) {
            $configForChannel = $this->config[$channel] ?? $defaultBroadcastingChannelConfig;

            if ($configForChannel->onlyEventName) {
                $message = $messageParser->getMessageWhenEventHasOnlyName();
            } else {
                $message = $messageParser->getMessageWhenEventHasPath();
            }

            $this->rabbitMQManager->connection($this->connection)
                ->channel()
                ->basic_publish(
                    $message,
                    $channel,
                    $configForChannel->routingKey,
                );
        }
    }
}
