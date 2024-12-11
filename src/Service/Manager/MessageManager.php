<?php

namespace App\Service\Manager;

use App\Entity\Message;
use App\Entity\Trick;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;

class MessageManager
{
    private EntityManagerInterface $entityManager;
    private MessageRepository $messageRepository;

    public function __construct(EntityManagerInterface $entityManager, MessageRepository $messageRepository)
    {
        $this->entityManager = $entityManager;
        $this->messageRepository = $messageRepository;
    }

    public function persistAndFlushMessage(Message $message):void
    {
        $this->entityManager->persist($message);
        $this->entityManager->flush();
    }

    public function getMessagesFromTrick(Trick $trick): Query
    {
        return $this->messageRepository->findByTrick($trick);
    }
}