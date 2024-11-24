<?php

declare(strict_types=1);

namespace Sn1KeRs\LaravelRabbitmq;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class RabbitMQConnection extends AMQPStreamConnection
{
    private ?int $chanelNullId = null;

    public function channel($channel_id = null): AMQPChannel
    {
        if (null !== $channel_id) {
            return parent::channel($channel_id);
        }

        if (null !== $this->chanelNullId) {
            return parent::channel($this->chanelNullId);
        }

        $chanel = parent::channel($channel_id);
        $this->chanelNullId = $chanel->getChannelId();

        return $chanel;
    }
}
