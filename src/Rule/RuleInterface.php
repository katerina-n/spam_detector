<?php

declare(strict_types=1);

namespace App\Rule;

use App\Stats\UserStats;

interface RuleInterface
{
    public function getName(): string;

    public function check(UserStats $stats): ?RuleResult;
}
