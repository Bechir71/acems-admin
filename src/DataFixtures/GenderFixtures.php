<?php

namespace App\DataFixtures;

use App\Entity\Gender;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class GenderFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $values = [Gender::MALE, Gender::FEMALE];

        foreach ($values as $value) {
            $gender = new Gender();
            $gender->setValue($value);

            $manager->persist($gender);
        }

        $manager->flush();
    }
}
