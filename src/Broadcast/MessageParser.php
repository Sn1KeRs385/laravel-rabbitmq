<?php

declare(strict_types=1);

namespace Sn1KeRs\LaravelRabbitmq\Broadcast;

use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPTable;
use ReflectionException;

class MessageParser
{
    private ?AMQPMessage $messageWhenEventHasPath = null;
    private ?AMQPMessage $messageWhenEventHasOnlyName = null;

    /** @var array<string, mixed> */
    private array $defaultHeaders = [];
    private string $payloadString;

    /**
     * @param array<mixed> $payload
     *
     * @throws ReflectionException
     */
    public function __construct(private readonly string $eventName, array $payload)
    {
        if (isset($payload['rmqHeaders'])) {
            foreach($payload['rmqHeaders'] as $key => $header) {
                $this->defaultHeaders[$key] = $header;
            }
            unset($payload['rmqHeaders']);
        }

        $this->payloadString = json_encode($payload);
    }

    public function getMessageWhenEventHasPath(): AMQPMessage
    {
        if (!$this->messageWhenEventHasPath) {
            return $this->parseMessageWhenEventHasPath();
        }

        return $this->messageWhenEventHasPath;
    }

    public function getMessageWhenEventHasOnlyName(): AMQPMessage
    {
        if (!$this->messageWhenEventHasOnlyName) {
            return $this->parseMessageWhenEventHasOnlyName();
        }

        return $this->messageWhenEventHasOnlyName;
    }

    private function parseMessageWhenEventHasPath(): AMQPMessage
    {
        $headersArray = ['eventName' => $this->eventName, ...$this->defaultHeaders];

        $message = new AMQPMessage($this->payloadString);
        $headers = new AMQPTable($headersArray);
        $message->set('application_headers', $headers);

        return $message;
    }

    private function parseMessageWhenEventHasOnlyName(): AMQPMessage
    {
        $eventName = substr($this->eventName, strrpos($this->eventName, '\\') + 1);

        $headersArray = ['eventName' => $eventName, ...$this->defaultHeaders];

        $message = new AMQPMessage($this->payloadString);
        $headers = new AMQPTable($headersArray);
        $message->set('application_headers', $headers);

        return $message;
    }
}
