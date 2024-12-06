<?php

namespace App\Service;

use App\DTO\TrickDTO;
use App\Entity\Illustration;
use App\Entity\Trick;
use App\Service\Manager\TrickManager;
use App\Service\Paginator\PaginatorService;
use Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\FormInterface;
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
     * @param TrickDTO $trickDTO
     * @return void
     * @throws Exception
     */
    public function createTrick(TrickDTO $trickDTO): void
    {
        $trick = new Trick();
        $trick->setName($trickDTO->name);
        $trick->setDescription($trickDTO->description);
        $trick->setTrickGroup($trickDTO->trickGroup);
        $trick->generateSlug($this->slugger);

        foreach ($trickDTO->illustrations as $illustrationDTO) {
            $illustration = new Illustration();
            $illustration->setTitle($illustrationDTO->title);

            // Handle file upload
            $uploadedFile = $illustrationDTO->uploadedFile;
            if ($uploadedFile) {
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

        $this->trickManager->persistAndFlushTrick($trick);

//        $videosData = $form->get('videos')->getData();
//
//        $videos = explode(',', $videosData);
//
//        foreach ($videos as $video) {
//            $newVideo = new Video();
//            $newVideo->setEmbedLink($video);
//            $newVideo->setTitle('Video');
//            $newVideo->setTrick($trick);
//            $trick->addVideo($newVideo);
//        }
//
//        $trick->generateSlug($slugger);
//        $this->persistAndFlushTrick($trick);
    }

    public function createTrickDTO(Trick $trick): TrickDTO
    {
        $trickDTO = new TrickDTO();
        $trickDTO->name = $trick->getName();
        $trickDTO->description = $trick->getDescription();
        $trickDTO->trickGroup = $trick->getTrickGroup();
//        $trickDTO->illustrations = $trick->getIllustrations()->toArray();
//        $trickDTO->videos = $trick->getVideos()->toArray();
        return $trickDTO;
    }

    public function editTrick(TrickDTO $trickDTO)
    {

    }
}