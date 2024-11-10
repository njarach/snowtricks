<?php

namespace App\Controller;

use App\Service\TrickService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    private TrickService $trickService;
    public function __construct(TrickService $trickService)
    {
        $this->trickService = $trickService;
    }

    /**
     * Returns the homepage of the website along with a display of tricks as a grid.
     * @return Response
     */
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $tricks = $this->trickService->getPaginatedTricksHomepage();
        return $this->render('home/index.html.twig', [
            'tricks'=>$tricks
        ]);
    }

    /**
     *
     */
    #[Route('/load-more-tricks', name:'app_load_more_tricks', methods: 'GET')]
    public function loadMoreTricks(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $tricks = $this->trickService->getPaginatedTricksHomepage($page);

        return $this->json([
            'tricks' => $this->renderView('trick/_tricks_list.html.twig', ['tricks' => $tricks]),
            'nextPage' => $page + 1,
        ]);
    }
}
