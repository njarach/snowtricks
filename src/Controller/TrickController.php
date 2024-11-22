<?php

namespace App\Controller;

use App\Entity\Illustration;
use App\Entity\Trick;
use App\Entity\Video;
use App\Form\TrickType;
use App\Service\TrickService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

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
        $page = $request->query->getInt('page', 1);
        $paginationData = $this->trickService->getPaginatedTricks($page);
        return $this->render('trick/index.html.twig', [
            'tricks' => $paginationData['items'],
            'currentPage' => $paginationData['currentPage'],
            'totalPages' => $paginationData['totalPages'],
        ]);
    }

    #[Route('/trick/{slug}', name: 'app_trick_show', requirements: ['slug' => '[a-z0-9\-]+'])]
    public function show(EntityManagerInterface $entityManager, string $slug): Response
    {
        $trick = $entityManager->getRepository(Trick::class)->findOneBy(['slug' => $slug]);
        return $this->render('trick/show.html.twig', [
            'trick' => $trick
        ]);
    }

    #[Route('/create-trick', name: 'app_create_trick')]
    #[IsGranted('ROLE_VERIFIED_USER')]
    public function new(Request $request, SluggerInterface $slugger): Response
    {
        $trick = new Trick();
        $form = $this->createForm(TrickType::class, $trick);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->trickService->handleTrickTypeData($form, $trick, $slugger);
            $this->addFlash('success', 'Le Trick a été créé avec succès.');
            return $this->redirectToRoute('app_trick_index');
        }

        return $this->render('trick/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/trick/edit/{id}', name: 'app_trick_edit')]
    #[IsGranted('ROLE_VERIFIED_USER')]
    public function edit(Request $request, SluggerInterface $slugger, Trick $trick): Response
    {
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->trickService->handleTrickTypeData($form, $trick, $slugger);
            $this->addFlash('success', 'Le Trick a été modifié avec succès.');
            return $this->redirectToRoute('app_trick_index');
        }

        return $this->render('trick/edit.html.twig', [
            'form' => $form->createView(),
            'trick' => $trick,
        ]);
    }

    #[Route('/trick/delete/{id}', name: 'app_trick_delete')]
    #[IsGranted('ROLE_VERIFIED_USER')]
    public function delete(Request $request, Trick $trick): Response
    {
        if ($this->isCsrfTokenValid('delete' . $trick->getId(), $request->request->get('_token'))) {
            $this->trickService->removeTrick($trick);

            $this->addFlash('success', 'Le Trick a été supprimé avec succès.');
        } else {
            $this->addFlash('error', 'Token CRSF invalide.');
        }

        return $this->redirectToRoute('app_trick_index');
    }

    #[Route('/illustration/delete/{id}', name: 'app_illustration_delete')]
    #[IsGranted('ROLE_VERIFIED_USER')]
    public function deleteIllustration(Request $request, EntityManagerInterface $entityManager, int $id): RedirectResponse
    {
        $illustration = $entityManager->getRepository(Illustration::class)->findOneBy(['id'=>$id]);
        $trick = $illustration->getTrick();

        if (!$this->isCsrfTokenValid('delete' . $illustration->getId(), $request->request->get('_token'))) {
            $this->addFlash('error', 'Invalid CSRF token');
            return $this->redirectToRoute('app_trick_edit', ['id' => $trick->getId()]);
        }

        // Delete the image file
        $filename = $illustration->getFileName();
        $uploadsDir = $this->getParameter('uploads_directory');
        unlink($uploadsDir . '/' . $filename);

        // Remove the illustration entity
        $entityManager->remove($illustration);
        $entityManager->flush();

        $this->addFlash('success', "L'image a été supprimée avec succès.");
        return $this->redirectToRoute('app_trick_edit', ['id' => $trick->getId()]);
    }

    #[Route('/video/delete/{id}', name: 'app_video_delete')]
    #[IsGranted('ROLE_VERIFIED_USER')]
    public function deleteVideo(Request $request, Video $video, EntityManagerInterface $entityManager): RedirectResponse
    {
        $trick = $video->getTrick();

        if (!$this->isCsrfTokenValid('delete' . $video->getId(), $request->request->get('_token'))) {
            $this->addFlash('error', 'Token CSRF invalide.');
            return $this->redirectToRoute('app_trick_edit', ['id' => $trick->getId()]);
        }

        $entityManager->remove($video);
        $entityManager->flush();

        $this->addFlash('success', 'La vidéo a été supprimée avec succès.');
        return $this->redirectToRoute('app_trick_edit', ['id' => $trick->getId()]);
    }
}
