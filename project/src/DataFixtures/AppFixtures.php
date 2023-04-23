<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    public function __construct(private readonly UserPasswordHasherInterface $hasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail("tonyblard55@gmail.com")->setRoles([])->setPlainPassword("tada")->setVerified(true);
        $user->setPassword($this->hasher->hashPassword($user,$user->getPlainPassword()));
        $user->eraseCredentials();
        $manager->persist($user);
        $manager->flush();
    }
}
