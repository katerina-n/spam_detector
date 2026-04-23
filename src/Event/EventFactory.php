<?php

declare(strict_types=1);

namespace App\Event;

use InvalidArgumentException;

class EventFactory
{
    public function fromArray(array $data): EventInterface
    {
        $type = $data['type'] ?? null;

        return match ($type) {
            'message' => new MessageEvent(
                (int)$data['sender_id'],
                (int)$data['receiver_id'],
                (string)$data['text'],
                (int)$data['ts'],
            ),
            'block' => new BlockEvent(
                (int)$data['blocker_id'],
                (int)$data['blocked_id'],
                (int)$data['ts'],
            ),
            'complaint' => new ComplaintEvent(
                (int)$data['complainant_id'],
                (int)$data['violator_id'],
                (int)$data['ts'],
            ),
            default => throw new InvalidArgumentException('Unknown event type'),
        };
    }
}
