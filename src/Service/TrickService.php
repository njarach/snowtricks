<?php

namespace App\Service;

use App\Entity\Trick;
use App\Service\Manager\TrickManager;
use App\Service\Paginator\PaginatorService;
use Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;

class TrickService
{
    private TrickManager $trickManager;
    private ParameterBagInterface $parameterBag;
    private SluggerInterface $slugger;

    public function __construct(TrickManager $trickManager, ParameterBagInterface $parameterBag, SluggerInterface $slugger)
    {
        $this->trickManager = $trickManager;
        $this->parameterBag = $parameterBag;
        $this->slugger = $slugger;
    }

    public function getPaginatedTricks(int $page = 1, int $limit = 5): array
    {
        $query = $this->trickManager->getAllTricks();
        $paginator = new PaginatorService();
        return $paginator->paginate($query, $page, $limit);
    }

    /**
     * @param Trick $trick
     * @return void
     */
    public function persistAndFlushTrick(Trick $trick): void
    {
        $this->trickManager->persistAndFlushTrick($trick);
    }

    /**
     * @param Trick $trick
     * @return void
     */
    public function removeTrick(Trick $trick): void
    {
        $this->trickManager->removeTrick($trick);
    }

    public function getLatestTricks(): array
    {
        return $this->trickManager->getLatestTricks();
    }

    /**
     * @param Trick $trick
     * @return void
     * @throws Exception
     */
    public function createTrick(Trick $trick,): void
    {
        $this->handleTrickFormData($trick);
    }

    /**
     * @throws Exception
     */
    public function editTrick(Trick $trick): void
    {
        $this->handleTrickFormData($trick);
    }

    /**
     * @param Trick $trick
     * @return void
     * @throws Exception
     */
    private function handleTrickFormData(Trick $trick): void
    {
        foreach ($trick->getIllustrations() as $illustration) {
            $uploadedFile = $illustration->getUploadedFile();
            if (!empty($uploadedFile)) {
                $newFilename = uniqid() . '.' . $uploadedFile->guessExtension();
                try {
                    $uploadedFile->move(
                        $this->parameterBag->get('uploads_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    throw new \Exception('File upload failed: ' . $e->getMessage());
                }
                $illustration->setFileName($newFilename);
            }
            $trick->addIllustration($illustration);
        }
        foreach ($trick->getVideos() as $video) {
            if (empty($video)) {
                $trick->removeVideo($video);
            }
        }
        $trick->generateSlug($this->slugger);
        $this->trickManager->persistAndFlushTrick($trick);
    }
}