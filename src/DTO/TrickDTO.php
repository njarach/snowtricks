<?php

namespace App\DTO;

use App\Entity\Group;
use App\Entity\Trick;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[UniqueEntity(
    fields: ['name'],
    entityClass: Trick::class,
)]
class TrickDTO
{
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    public ?string $name = null;

    #[Assert\NotBlank]
    public ?string $description = null;

    #[Assert\NotBlank]
    public ?Group $trickGroup = null;

    /**
     * @var IllustrationDTO[]
     */
    #[Assert\Valid]
    public array $illustrations = [];

    /**
     * @var VideoDTO[] Optional, if videos are part of the form
     */
    #[Assert\Valid]
    public array $videos = [];
}