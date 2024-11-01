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
    public function index(): Response
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
            $this->trickService->persistTrick($trick);
            $this->addFlash('success', 'Le Trick a été créé avec succès !');
            return $this->redirectToRoute('app_trick_index');
        }

        return $this->render('trick/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/trick/edit/{id}', name: 'app_trick_edit')]
    public function edit(Request $request, Trick $trick): Response
    {
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->trickService->persistTrick($trick);
            $this->addFlash('success','Le Trick a été modifié avec succès.');
            return $this->redirectToRoute('app_trick_index');
        }

        return $this->render('trick/edit.html.twig', [
            'form' => $form->createView(),
            'trick' => $trick,
        ]);
    }

    #[Route('/trick/delete/{id}', name: 'app_trick_delete')]
    public function delete(Request $request, Trick $trick): Response
    {
        if ($this->isCsrfTokenValid('delete'.$trick->getId(), $request->request->get('_token'))) {
            $this->trickService->removeTrick($trick);

            $this->addFlash('success', 'Le Trick a été supprimé.');
        } else {
            $this->addFlash('error', 'Token CRSF invalide.');
        }

        return $this->redirectToRoute('app_trick_index');
    }
}
