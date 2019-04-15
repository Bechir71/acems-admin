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
            'Président',
            'Vice Président',
            'Secrétaire Général·e',
            'Secrétaire Général adjoint·e',
            'Trésorier·e Général·e',
            'Trésorier·e Général·e adjoint·e',
            'Commissaire au compte',
            'Commissaire au compte adjoint·e',
            'Chargé·e de la communication',
            'Chargé·e de la communication adjoint·e',
            'Chargé·e de la commission féminine',
            'Chargé·e de la commission féminine adjoint·e',
            'Chargé·e des relations extérieures',
            'Chargé·e des relations extérieures adjoint·e',
            'Chargé·e de la commission d\'innovation',
            'Chargé·e de la commission d\'innovation adjoint·e',
            'Chargé·e de la commission d\'organisation',
            'Chargé·e de la commission d\'organisation adjoint·e',
            'Chargé·e de la commission sociale et culturelle',
            'Chargé·e de la commission sociale et culturelle adjoint·e',
            'Chargé·e de la commission pédagogique et scientifique',
            'Chargé·e de la commission pédagogique et scientifique adjoint·e',
        ];

        foreach ($names as $name) {
            $post = new Post();
            $post->setName($name);

            $manager->persist($post);
        }

        $manager->flush();
    }
}
