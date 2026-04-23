<?php

declare(strict_types=1);

namespace App\Stats;

class UserStats
{
    private int $messagesSent = 0;
    private int $messagesReceived = 0;
    private int $blocksReceived = 0;
    private int $complaintsReceived = 0;

    /** @var array<int, true> */
    private array $uniqueReceivers = [];

    public function __construct(
        private int $userId
    ) {
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function addSentMessageTo(int $receiverId): void
    {
        $this->messagesSent++;
        $this->uniqueReceivers[$receiverId] = true;
    }

    public function addReceivedMessage(): void
    {
        $this->messagesReceived++;
    }

    public function addBlockReceived(): void
    {
        $this->blocksReceived++;
    }

    public function addComplaintReceived(): void
    {
        $this->complaintsReceived++;
    }

    public function getMessagesSent(): int
    {
        return $this->messagesSent;
    }

    public function getMessagesReceived(): int
    {
        return $this->messagesReceived;
    }

    public function getBlocksReceived(): int
    {
        return $this->blocksReceived;
    }

    public function getComplaintsReceived(): int
    {
        return $this->complaintsReceived;
    }

    public function getUniqueReceiversCount(): int
    {
        return \count($this->uniqueReceivers);
    }
}
