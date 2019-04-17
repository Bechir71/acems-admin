<?php

namespace App\DataFixtures;

use App\Entity\Address;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AddressFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $names = [
            'Village A', 'Village B', 'Village C', 'Village D', 'Village E',
            'Village F', 'Village G', 'Village H', 'Village I', 'Village J',
            'Village K', 'Village L', 'Village M', 'Village N', 'Village O',
            'Sanar', 'Ngallel', 'Boudiouk', 'Ville',
        ];

        foreach ($names as $name) {
            $address = new Address();
            $address->setName($name);

            $manager->persist($address);
        }

        $manager->flush();
    }
}
