<?php

namespace App\Service;

use App\Entity\Illustration;
use App\Entity\Trick;
use App\Entity\Video;
use App\Service\Manager\TrickManager;
use App\Service\Paginator\PaginatorService;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class TrickService
{
    private TrickManager $trickManager;
    private ParameterBagInterface $parameterBag;
    public function __construct(TrickManager $trickManager, ParameterBagInterface $parameterBag)
    {
        $this->trickManager = $trickManager;
        $this->parameterBag = $parameterBag;
    }

    public function getPaginatedTricks(int $page = 1, int $limit = 5): array
    {
        $query = $this->trickManager->getAllTricks();
        $paginator = new PaginatorService();
        return $paginator->paginate($query,$page,$limit);
    }

    /**
     * @param Trick $trick
     * @return void
     */
    public function persistAndFlushTrick(Trick $trick):void
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
     * @param FormInterface $form
     * @param Trick $trick
     * @param SluggerInterface $slugger
     * @return void
     */
    public function handleTrickTypeData(FormInterface $form, Trick $trick, SluggerInterface $slugger): void
    {
        $illustrations = $form->get('illustrations')->getData();

        foreach ($illustrations as $image) {
            // TODO : move this to an upload service
            $file = md5(uniqid()) . '.' . $image->guessExtension();
            $image->move(
                $this->parameterBag->get('uploads_directory'),
                $file
            );

            $newIllustration = new Illustration();
            $newIllustration->setFileName($file);
            $newIllustration->setTitle("Image");
            $trick->addIllustration($newIllustration);
        }

        $videosData = $form->get('videos')->getData();
        $videos = explode(',', $videosData);
        foreach ($videos as $video) {
            $newVideo = new Video();
            $newVideo->setEmbedLink($video);
            $newVideo->setTitle('Video');
            $newVideo->setTrick($trick);
            $trick->addVideo($newVideo);
            //TODO url format verification if not correctly done by type ?
        }

        $trick->generateSlug($slugger);
        $this->persistAndFlushTrick($trick);
    }
}