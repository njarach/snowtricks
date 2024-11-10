<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Form\TrickType;
use App\Service\TrickService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

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
        $page = $request->query->getInt('page',1);
        $paginationData = $this->trickService->getPaginatedTricks($page);
        return $this->render('trick/index.html.twig',[
            'tricks' => $paginationData['items'],
            'currentPage' => $paginationData['currentPage'],
            'totalPages' => $paginationData['totalPages'],
            ]);
    }

    #[Route('/trick/{slug}', name: 'app_trick_show', requirements: ['slug' => '[a-z0-9\-]+'])]
    public function show(EntityManagerInterface $entityManager, string $slug): Response
    {
        $trick = $entityManager->getRepository(Trick::class)->findOneBy(['slug'=>$slug]);
        return $this->render('trick/show.html.twig',[
            'trick'=>$trick
        ]);
    }

    #[Route('/create-trick', name: 'app_create_trick')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
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
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
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
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
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
