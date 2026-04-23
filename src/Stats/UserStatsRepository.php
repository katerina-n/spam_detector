<?php

declare(strict_types=1);

namespace App\Stats;

class UserStatsRepository
{
    /** @var array<int, UserStats> */
    private array $items = [];

    public function get(int $userId): UserStats
    {
        if (!isset($this->items[$userId])) {
            $this->items[$userId] = new UserStats($userId);
        }

        return $this->items[$userId];
    }

    /**
     * @return UserStats[]
     */
    public function all(): array
    {
        return \array_values($this->items);
    }
}
