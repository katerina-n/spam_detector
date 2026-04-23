<?php

declare(strict_types=1);

namespace App\Rule;

use App\Stats\UserStats;

class HighBlockRateRule implements RuleInterface
{
    public function __construct(
        private int $minMessagesSent = 10,
        private float $minBlockRate = 0.3
    ) {
    }

    public function getName(): string
    {
        return 'high_block_rate';
    }

    public function check(UserStats $stats): ?RuleResult
    {
        $messagesSent = $stats->getMessagesSent();
        $blocksReceived = $stats->getBlocksReceived();

        if ($messagesSent < $this->minMessagesSent) {
            return null;
        }

        $blockRate = $blocksReceived / $messagesSent;

        if ($blockRate >= $this->minBlockRate) {
            return new RuleResult(
                $this->getName(),
                [
                    'messages_sent' => $messagesSent,
                    'blocks_received' => $blocksReceived,
                    'block_rate' => round($blockRate, 2),
                ]
            );
        }

        return null;
    }
}
