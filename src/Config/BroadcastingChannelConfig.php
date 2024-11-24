<?php

declare(strict_types=1);

namespace Sn1KeRs\LaravelRabbitmq\Config;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapInputName(SnakeCaseMapper::class)]
class BroadcastingChannelConfig extends Data
{
    public function __construct(
        public readonly string $routingKey = '',
        public readonly bool $onlyEventName = false,
    ) {
    }
}
