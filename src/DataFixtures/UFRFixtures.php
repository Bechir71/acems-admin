<?php

namespace App\DataFixtures;

use App\Entity\UFR;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class UFRFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $names = ['SAT', 'SEG', 'LSH', 'SJP', 'S2ATA', 'SEFS', 'CRAC', '2S', 'IPSL'];
        sort($names);

        foreach ($names as $name) {
            $ufr = new UFR();
            $ufr->setName($name);

            $manager->persist($ufr);
        }

        $manager->flush();
    }
}
