<?php

declare(strict_types=1);

namespace App\Rule;

class RuleResult
{
    public function __construct(
        private string $ruleName,
        private array $evidence
    ) {
    }

    public function toArray(): array
    {
        return [
            'rule' => $this->ruleName,
            'evidence' => $this->evidence,
        ];
    }
}
