<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Trick;
use App\Service\MessageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class MessageBoardController extends AbstractController
{
    private MessageService $messageService;
    public function __construct(MessageService $messageService)
    {
        $this->messageService = $messageService;
    }

    #[Route('/add/message/{id}', name: 'app_create_message')]
    #[IsGranted('ROLE_VERIFIED_USER')]
    public function create(Request $request, Trick $trick): Response
    {
        $messageContent = $request->request->get('message_content');
        if (!empty($messageContent))
        {
            $newMessage = new Message();
            $newMessage->setAuthor($this->getUser());
            $newMessage->setTrick($trick);
            $this->messageService->createMessage($newMessage, $messageContent);
        }
        return $this->redirectToRoute('app_trick_show',['slug'=>$trick->getSlug()]);
    }
}
