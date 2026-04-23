<?php

declare(strict_types=1);

namespace App\Event;

class ComplaintEvent implements EventInterface
{
    public function __construct(
        private int $complainantId,
        private int $violatorId,
        private int $ts
    ) {
    }

    public function getType(): string
    {
        return 'complaint';
    }

    public function getTimestamp(): int
    {
        return $this->ts;
    }

    public function getComplainantId(): int
    {
        return $this->complainantId;
    }

    public function getViolatorId(): int
    {
        return $this->violatorId;
    }
}
