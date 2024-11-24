<?php

declare(strict_types=1);

namespace Sn1KeRs\LaravelRabbitmq;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;
use Sn1KeRs\LaravelRabbitmq\Broadcast\RabbitMQBroadcaster;
use Sn1KeRs\LaravelRabbitmq\Config\BroadcastingChannelConfig;
use Sn1KeRs\LaravelRabbitmq\Config\RabbitMQConfig;

class RabbitMQServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/rabbitmq.php', 'rabbitmq');

        $this->publishes([
            __DIR__ . '/../config/rabbitmq.php' => config_path('rabbitmq.php'),
        ], 'config');

        $config = RabbitMQConfig::from(config('rabbitmq'));

        $this->app->bind(RabbitMQConfig::class, function (Application $app) use ($config) {
            return $config;
        });

        $this->app->singleton('rabbitmq', function (Application $app) use ($config) {
            return new RabbitMQManager($app, $config);
        });

        Broadcast::extend('rabbitmq', function (Application $app, array $config) {
            /** @var BroadcastingChannelConfig[] $channelsConfig */
            $channelsConfig = [];
            if (isset($config['channels_config'])) {
                foreach ($config['channels_config'] as $channelKey => $channelConfig) {
                    $channelsConfig[$channelKey] = BroadcastingChannelConfig::from($channelConfig);
                }
            }

            /** @var RabbitMQManager $rabbitMQManager */
            $rabbitMQManager = $app->get('rabbitmq');

            return new RabbitMQBroadcaster(
                $app,
                $rabbitMQManager,
                $channelsConfig,
                $config['connection'] ?? 'default'
            );
        });
    }
}
