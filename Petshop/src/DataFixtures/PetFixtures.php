<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Pet;

class PetFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for($i = 1; $i <= 10; $i++)
        {
            $pet = new Pet();
            $pet->setPid("$i")
                ->setName('Cat')
                ->setContent("Really cool pet")
                ->setPrice(10)
                ->setImage("http://placehold.it/350x150");
            $manager->persist($pet);
        }

        $manager->flush();
    }
}
