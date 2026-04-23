<?php

declare(strict_types=1);

namespace App\Processor;

use App\Event\BlockEvent;
use App\Event\ComplaintEvent;
use App\Event\EventInterface;
use App\Event\MessageEvent;
use App\Stats\UserStatsRepository;

class EventProcessor
{
    /** @var array<class-string<EventInterface>, string> */
    private array $handlers = [
        MessageEvent::class => 'processMessage',
        BlockEvent::class => 'processBlock',
        ComplaintEvent::class => 'processComplaint',
    ];

    public function __construct(
        private UserStatsRepository $repository
    ) {
    }

    public function process(EventInterface $event): void
    {
        $handler = $this->handlers[$event::class] ?? null;
        if ($handler === null) {
            return;
        }

        $this->$handler($event);
    }

    private function processMessage(MessageEvent $event): void
    {
        $senderStats = $this->repository->get($event->getSenderId());
        $senderStats->addSentMessageTo($event->getReceiverId());

        $receiverStats = $this->repository->get($event->getReceiverId());
        $receiverStats->addReceivedMessage();
    }

    private function processBlock(BlockEvent $event): void
    {
        $blockedStats = $this->repository->get($event->getBlockedId());
        $blockedStats->addBlockReceived();
    }

    private function processComplaint(ComplaintEvent $event): void
    {
        $violatorStats = $this->repository->get($event->getViolatorId());
        $violatorStats->addComplaintReceived();
    }
}
