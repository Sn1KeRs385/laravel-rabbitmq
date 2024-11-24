<?php

declare(strict_types=1);

namespace Sn1KeRs\LaravelRabbitmq\Config;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapInputName(SnakeCaseMapper::class)]
class RabbitMQConfig extends Data
{
    public function __construct(
        /**
         * @var array<string, ConnectionItemConfig>
         */
        public array $connections,
    ) {
        foreach ($this->connections as &$connection) {
            $connection = ConnectionItemConfig::from($connection);
        }

        unset($connection);
    }
}
