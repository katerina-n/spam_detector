<?php

declare(strict_types=1);

namespace App\Rule;

use App\Stats\UserStats;

class ManyUniqueReceiversRule implements RuleInterface
{
    public function __construct(
        private int $minMessagesSent = 20,
        private int $minUniqueReceivers = 15
    ) {
    }

    public function getName(): string
    {
        return 'many_unique_receivers';
    }

    public function check(UserStats $stats): ?RuleResult
    {
        $messagesSent = $stats->getMessagesSent();
        $uniqueReceivers = $stats->getUniqueReceiversCount();

        if (
            $messagesSent >= $this->minMessagesSent
            && $uniqueReceivers >= $this->minUniqueReceivers
        ) {
            return new RuleResult(
                $this->getName(),
                [
                    'messages_sent' => $messagesSent,
                    'unique_receivers' => $uniqueReceivers,
                ]
            );
        }

        return null;
    }
}
