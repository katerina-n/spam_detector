<?php

declare(strict_types=1);

namespace App\Engine;

use App\Rule\RuleInterface;
use App\Stats\UserStatsRepository;

final class RulesEngine
{
    /**
     * @param RuleInterface[] $rules
     */
    public function __construct(
        private array $rules
    ) {
    }

    public function evaluate(UserStatsRepository $repository): array
    {
        $result = [];

        foreach ($repository->all() as $userStats) {
            $triggeredRules = [];

            foreach ($this->rules as $rule) {
                $ruleResult = $rule->check($userStats);

                if ($ruleResult !== null) {
                    $triggeredRules[] = $ruleResult->toArray();
                }
            }

            if ($triggeredRules !== []) {
                $result[] = [
                    'user_id' => $userStats->getUserId(),
                    'triggered_rules' => $triggeredRules,
                ];
            }
        }

        return $result;
    }
}
