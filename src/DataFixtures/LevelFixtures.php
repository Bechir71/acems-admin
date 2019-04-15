<?php

namespace App\DataFixtures;

use App\Entity\Level;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class LevelFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $names = [
            'Licence 1', 'Licence 2', 'Licence 3', 'Master 1', 'Master 2', 'Doctorat'
        ];

        foreach ($names as $name) {
            $level = new Level();
            $level->setName($name);

            $manager->persist($level);
        }

        $manager->flush();
    }
}
