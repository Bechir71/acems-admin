<?php

namespace App\DataFixtures;

use App\Entity\Post;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class PostFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $names = [
            'President',
            'Secretaire Generale', 'Secretaire Generale Adjoint',
        ];

        foreach ($names as $name) {
            $post = new Post();
            $post->setName($name);

            $manager->persist($post);
        }

        $manager->flush();
    }
}
