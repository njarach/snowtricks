<?php

namespace App\Service;

use App\Entity\Illustration;
use App\Entity\Trick;
use App\Entity\Video;
use App\Service\Manager\MessageManager;
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
    private MessageManager $messageManager;
    private ParameterBagInterface $parameterBag;
    private SluggerInterface $slugger;

    public function __construct(TrickManager $trickManager, MessageManager $messageManager, ParameterBagInterface $parameterBag, SluggerInterface $slugger)
    {
        $this->trickManager = $trickManager;
        $this->parameterBag = $parameterBag;
        $this->slugger = $slugger;
        $this->messageManager = $messageManager;
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
    public function cleanupAndPersistTrickData(Trick $trick): void
    {
        $this->removeEmptyLinkVideos($trick);
        $this->removeEmptyFilenameIllustrations($trick);
        $trick->generateSlug($this->slugger);
        $this->persistAndFlushTrick($trick);
    }

    /**
     * @param Trick $trick
     * @return void
     */
    private function removeEmptyLinkVideos(Trick $trick): void
    {
        foreach ($trick->getVideos() as $video) {
            if (empty($video->getEmbedLink())) {
                $trick->removeVideo($video);
            }
        }
    }

    /**
     * @param Trick $trick
     * @return void
     */
    private function removeEmptyFilenameIllustrations(Trick $trick): void
    {
        foreach ($trick->getIllustrations() as $illustration) {
            if (empty($illustration->getFileName())) {
                $trick->removeIllustration($illustration);
            }
        }
    }

    public function getPaginatedMessages(Trick $trick, int $page, int $limit = 10): array
    {
        $query = $this->messageManager->getMessagesFromTrick($trick);
        $paginator = new PaginatorService();
        return $paginator->paginate($query,$page, $limit);
    }

    public function bindVideoLinks(FormInterface $form, Trick $trick): void
    {
        $videos = $form->get('videos')->getData();

        foreach ($videos as $videoData) {
            $video = new Video();
            $video->setEmbedLink($videoData->getEmbedLink());
            $trick->addVideo($video);
        }
    }

    /**
     * @throws Exception
     */
    public function bindIllustrationFilename(FormInterface $form, Trick $trick): void
    {
        $illustrations = $form->get('illustrations')->getData();

        foreach ($illustrations as $illustration) {
            $newIllustration = new Illustration();
            $uploadedFile = $illustration->getUploadedFile();
            if (!empty($uploadedFile)) {
                $newFilename = uniqid() . '.' . $uploadedFile->guessExtension();
                try {
                    $uploadedFile->move(
                        $this->parameterBag->get('images_uploads_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    throw new Exception('File upload failed: ' . $e->getMessage());
                }
                $newIllustration->setFileName($newFilename);
                $trick->addIllustration($newIllustration);
            }
        }
    }
}