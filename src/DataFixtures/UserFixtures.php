<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

use App\Entity\User;
use App\Entity\Address;
use App\Entity\Level;
use App\Entity\Post;
use App\Entity\UFR;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $address = $manager->getRepository(Address::class)->findAll()[0];
        $level = $manager->getRepository(Level::class)->findAll()[0];
        $post = $manager->getRepository(Post::class)->findAll()[0];
        $ufr = $manager->getRepository(UFR::class)->findAll()[0];

        $user = new User();
        $user->setUsername('Admin')
            ->setEmail('acems-admin@gmail.com')
            ->setPassword($this->passwordEncoder->encodePassword($user, '@C3M$'))
            ->setEnabled(true)
            ->setRoles(['ROLE_SUPER_ADMIN'])
            ->setAddress($address)
            ->setLevel($level)
            ->setPost($post)
            ->setUfr($ufr)
        ;

        $manager->persist($user);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            AddressFixtures::class,
            LevelFixtures::class,
            PostFixtures::class,
            UFRFixtures::class
        ];
    }
}
