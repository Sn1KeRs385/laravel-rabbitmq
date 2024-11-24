<?php

declare(strict_types=1);

namespace Sn1KeRs\LaravelRabbitmq\Events;

use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ExampleEvent implements ShouldBroadcast
{
    use Dispatchable;
    use SerializesModels;

    /**
     * @param int $exampleVariable
     * @param array<string, mixed>|null $rmqHeaders - Задает хедеры для сообщения в рэбите. Удаляются из payload
     */
    public function __construct(
        public int $exampleVariable,
        public ?array $rmqHeaders,
    ) {
    }

    public function broadcastOn(): string
    {
        return 'socket';
    }
}
