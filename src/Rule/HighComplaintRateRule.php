<?php

declare(strict_types=1);

namespace App\Rule;

use App\Stats\UserStats;

class HighComplaintRateRule implements RuleInterface
{
    public function __construct(
        private int $minMessagesSent = 10,
        private float $minComplaintRate = 0.02
    ) {
    }

    public function getName(): string
    {
        return 'high_complaint_rate';
    }

    public function check(UserStats $stats): ?RuleResult
    {
        $messagesSent = $stats->getMessagesSent();
        $complaintsReceived = $stats->getComplaintsReceived();

        if ($messagesSent < $this->minMessagesSent) {
            return null;
        }

        $complaintRate = $complaintsReceived / $messagesSent;

        if ($complaintRate >= $this->minComplaintRate) {
            return new RuleResult(
                $this->getName(),
                [
                    'messages_sent' => $messagesSent,
                    'complaints_received' => $complaintsReceived,
                    'complaint_rate' => round($complaintRate, 2),
                ]
            );
        }

        return null;
    }
}
