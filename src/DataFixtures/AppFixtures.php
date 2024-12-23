<?php

namespace App\DataFixtures;

use App\Entity\Group;
use App\Entity\Trick;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $user1 = new User();
        $user1->setEmail('user1@example.com');
        $user1->setPassword($this->passwordHasher->hashPassword($user1, 'password1'));
        $user1->setUsername('User1');
        $user1->setVerified(true);

        $user2 = new User();
        $user2->setEmail('user2@example.com');
        $user2->setPassword($this->passwordHasher->hashPassword($user2, 'password2'));
        $user2->setUsername('User2');
        $user2->setVerified(true);

        $manager->persist($user1);
        $manager->persist($user2);

        $grabGroup = $manager->getRepository(Group::class)->findOneBy(['name' => 'Grabs']);
        $oldSchoolGroup = $manager->getRepository(Group::class)->findOneBy(['name' => 'Old School']);
        $flipsGroup = $manager->getRepository(Group::class)->findOneBy(['name' => 'Flips']);

        $tricksData = [
            [
                'name' => 'Mute',
                'group' => $grabGroup,
                'description' => 'Saisie de la carre frontside de la planche entre les deux pieds avec la main avant.'
            ],
            [
                'name' => 'Sad',
                'group' => $grabGroup,
                'description' => 'Saisie de la carre backside de la planche, entre les deux pieds, avec la main avant.'
            ],
            [
                'name' => 'Indy',
                'group' => $grabGroup,
                'description' => 'Saisie de la carre frontside de la planche, entre les deux pieds, avec la main arrière.'
            ],
            [
                'name' => 'Stalefish',
                'group' => $grabGroup,
                'description' => 'Saisie de la carre backside de la planche entre les deux pieds avec la main arrière.'
            ],
            [
                'name' => 'Tail Grab',
                'group' => $grabGroup,
                'description' => 'Saisie de la partie arrière de la planche, avec la main arrière.'
            ],
            [
                'name' => 'Nose Grab',
                'group' => $grabGroup,
                'description' => 'Saisie de la partie avant de la planche, avec la main avant.'
            ],
            [
                'name' => 'Japan Air',
                'group' => $grabGroup,
                'description' => 'Saisie de l’avant de la planche, avec la main avant, du côté de la carre frontside.'
            ],
            [
                'name' => 'Backside Air',
                'group' => $oldSchoolGroup,
                'description' => 'Une figure old school classique.'
            ],
            [
                'name' => 'Method Air',
                'group' => $oldSchoolGroup,
                'description' => 'Une autre figure old school indémodable.'
            ],
            [
                'name' => 'Front Flip',
                'group' => $flipsGroup,
                'description' => 'Une rotation verticale en avant.'
            ]
        ];

        foreach ($tricksData as $index => $data) {
            $trick = new Trick();
            $trick->setName($data['name']);
            $trick->setTrickGroup($data['group']);
            $trick->setDescription($data['description']);
            $trick->setAuthor($index % 2 === 0 ? $user1 : $user2);
            $manager->persist($trick);
        }

        $manager->flush();
    }
}
