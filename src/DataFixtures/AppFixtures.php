<?php

namespace App\DataFixtures;

use App\Entity\Group;
use App\Entity\Illustration;
use App\Entity\Trick;
use App\Entity\User;
use App\Entity\Video;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;
    private SluggerInterface $slugger;

    public function __construct(UserPasswordHasherInterface $passwordHasher, SluggerInterface $slugger)
    {
        $this->passwordHasher = $passwordHasher;
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager): void
    {
        $user1 = new User();
        $user1->setEmail('user1@example.com');
        $user1->setPassword($this->passwordHasher->hashPassword($user1, 'password1'));
        $user1->setUsername('User1');
        $user1->setFirstname('Jean');
        $user1->setLastname('Dupont');
        $user1->setProfilePicture('uploads/profile_pictures/profile_picture_placeholder.png');
        $user1->setVerified(true);
        $user1->setRoles(['ROLE_VERIFIED_USER']);

        $user2 = new User();
        $user2->setEmail('user2@example.com');
        $user2->setPassword($this->passwordHasher->hashPassword($user2, 'password2'));
        $user2->setUsername('User2');
        $user2->setFirstname('Jeanne');
        $user2->setLastname('Doe');
        $user2->setProfilePicture('uploads/profile_pictures/profile_picture_placeholder.png');
        $user2->setVerified(true);
        $user2->setRoles(['ROLE_VERIFIED_USER']);

        $manager->persist($user1);
        $manager->persist($user2);
        $manager->flush();

        $groupNames = [
            'Grabs',
            'Rotations',
            'Rotations désaxées',
            'Flips',
            'Slides',
            'One foot',
            'Old school'
        ];

        $groups = [];
        foreach ($groupNames as $groupName) {
            $group = new Group();
            $group->setName($groupName);
            $manager->persist($group);
            $groups[$groupName] = $group;
        }

        $manager->flush();

        $tricksData = [
            [
                'name' => 'Mute',
                'group' => $groups['Grabs'],
                'description' => 'Saisie de la carre frontside de la planche entre les deux pieds avec la main avant.',
                'illustration' => ['mute_grab.jpg','placeholder.jpg','placeholder.jpg']
            ],
            [
                'name' => 'Sad',
                'group' => $groups['Grabs'],
                'description' => 'Saisie de la carre backside de la planche, entre les deux pieds, avec la main avant.',
                'illustration' => ['sad_grab.jpg','placeholder.jpg','placeholder.jpg']
            ],
            [
                'name' => 'Indy',
                'group' => $groups['Grabs'],
                'description' => 'Saisie de la carre frontside de la planche, entre les deux pieds, avec la main arrière.',
                'illustration' => ['indie_grab.jpg','placeholder.jpg','placeholder.jpg']
            ],
            [
                'name' => 'Stalefish',
                'group' => $groups['Grabs'],
                'description' => 'Saisie de la carre backside de la planche entre les deux pieds avec la main arrière.',
                'illustration' => ['stalefish_grab.jpg','placeholder.jpg','placeholder.jpg']
            ],
            [
                'name' => 'Tail Grab',
                'group' => $groups['Grabs'],
                'description' => 'Saisie de la partie arrière de la planche, avec la main arrière.',
                'illustration' => ['tail_grab.jpg','placeholder.jpg','placeholder.jpg']
            ],
            [
                'name' => 'Nose Grab',
                'group' => $groups['Grabs'],
                'description' => 'Saisie de la partie avant de la planche, avec la main avant.',
                'illustration' => ['nose_grab.jpg','placeholder.jpg','placeholder.jpg']
            ],
            [
                'name' => 'Japan Air',
                'group' => $groups['Grabs'],
                'description' => 'Saisie de l’avant de la planche, avec la main avant, du côté de la carre frontside.',
                'illustration' => ['japan_air.jpg','placeholder.jpg','placeholder.jpg']
            ],
            [
                'name' => 'Backside Air',
                'group' => $groups['Old school'],
                'description' => 'Une figure old school classique.',
                'illustration' => ['backside_air.jpg','placeholder.jpg','placeholder.jpg']
            ],
            [
                'name' => 'Method Air',
                'group' => $groups['Old school'],
                'description' => 'Une autre figure old school indémodable.',
                'illustration' => ['method_air.jpg','placeholder.jpg','placeholder.jpg']
            ],
            [
                'name' => 'Front Flip',
                'group' => $groups['Flips'],
                'description' => 'Une rotation verticale en avant.',
                'illustration' => ['front_flip.jpg','placeholder.jpg','placeholder.jpg'],
                'video' => 'https://www.youtube.com/embed/80pI61w_qtk?si=Yq5T4OUtDRYo6jSq'
            ]
        ];

        foreach ($tricksData as $index => $data) {
            $trick = new Trick();
            $trick->setCreatedAt(new \DateTimeImmutable('now'));
            $trick->setName($data['name']);
            $trick->setTrickGroup($data['group']);
            $trick->setDescription($data['description']);
            $trick->setAuthor($index % 2 === 0 ? $user1 : $user2);
            $trick->generateSlug($this->slugger);
            $manager->persist($trick);

            foreach ($data['illustration'] as $illustration) {
                $newIllustration = new Illustration();
                $newIllustration->setFilename($illustration);
                $newIllustration->setTrick($trick);
                $manager->persist($newIllustration);
            }


            if (!empty($data['video'])) {
                $video = new Video();
                $video->setEmbedLink($data['video']);
                $video->setTrick($trick);
                $manager->persist($video);
            }
        }
        $manager->flush();
    }
}
