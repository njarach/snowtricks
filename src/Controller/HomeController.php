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
        $tricks = $this->trickService->getLatestTricks();
        return $this->render('home/index.html.twig', [
            'tricks'=>$tricks
        ]);
    }
}
