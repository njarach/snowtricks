<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Form\TrickType;
use App\Service\TrickService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TrickController extends AbstractController
{
    private TrickService $trickService;
    public function __construct(TrickService $trickService)
    {
        $this->trickService = $trickService;
    }

    #[Route('/tricks-index', name: 'app_trick_index')]
    public function index(Request $request): Response
    {
        $tricks = $this->trickService->getPaginatedTricks(1,15);
        return $this->render('trick/index.html.twig',[
            'tricks'=>$tricks
        ]);
    }

    #[Route('/create-trick', name: 'app_create_trick')]
    public function new(Request $request): Response
    {
        $trick = new Trick();
        $form = $this->createForm(TrickType::class,$trick);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $this->trickService->createTrick($trick);

            $this->addFlash('success', 'Trick created successfully!');

            return $this->redirectToRoute('app_trick_index');
        }

        return $this->render('trick/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
