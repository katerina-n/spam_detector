<?php

declare(strict_types=1);

namespace App\Event;

class BlockEvent implements EventInterface
{
    public function __construct(
        private int $blockerId,
        private int $blockedId,
        private int $ts,
    ) {
    }

    public function getType(): string
    {
        return 'block';
    }

    public function getTimestamp(): int
    {
        return $this->ts;
    }

    public function getBlockerId(): int
    {
        return $this->blockerId;
    }

    public function getBlockedId(): int
    {
        return $this->blockedId;
    }
}
