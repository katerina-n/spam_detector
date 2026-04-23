<?php

declare(strict_types=1);

namespace App\Event;

class MessageEvent implements EventInterface
{
    public function __construct(
        private int $senderId,
        private int $receiverId,
        private string $text,
        private int $ts
    ) {
    }

    public function getType(): string
    {
        return 'message';
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getReceiverId(): int
    {
        return $this->receiverId;
    }

    public function getSenderId(): int
    {
        return $this->senderId;
    }

    public function getTimestamp(): int
    {
        return $this->ts;
    }
}