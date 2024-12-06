<?php

namespace App\DTO;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

class IllustrationDTO
{
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    public ?string $title = null;

    #[Assert\NotNull]
    #[Assert\File(maxSize: '2M', mimeTypes: ['image/jpeg', 'image/png'])]
    public ?File $uploadedFile = null;
}