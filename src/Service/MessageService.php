<?php

namespace App\Service;

use App\Entity\Message;
use App\Service\Manager\MessageManager;
use App\Service\Manager\TrickManager;
use DateTimeImmutable;

class MessageService
{
    private MessageManager $messageManager;
    private TrickManager $trickManager;

    public function __construct(MessageManager $messageManager, TrickManager $trickManager)
    {
        $this->messageManager = $messageManager;
        $this->trickManager = $trickManager;
    }

    public function createMessage(Message $message, string $messageContent): void
    {
        $message->setContent($messageContent);
        $message->setCreatedAt(new DateTimeImmutable());
        $this->persistAndFlushMessage($message);
    }

    private function persistAndFlushMessage(Message $message): void
    {
        $this->messageManager->persistAndFlushMessage($message);
    }
}