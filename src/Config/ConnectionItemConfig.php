<?php

declare(strict_types=1);

namespace Sn1KeRs\LaravelRabbitmq\Config;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapInputName(SnakeCaseMapper::class)]
class ConnectionItemConfig extends Data
{
    public function __construct(
        public readonly string $host,
        public readonly int $port,
        public readonly string $user,
        public readonly string $password,
        public readonly string $virtualHost,
    ) {
    }
}
