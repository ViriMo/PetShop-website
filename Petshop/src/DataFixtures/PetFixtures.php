<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Pet;
use Faker;

class PetFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        for($i = 0; $i < 8; $i++) {
            $user = new User();
            $user->setUsername($faker->name)
                 ->setRoles(array('ROLE_USER'))
                 ->setPassword('Password')
                 ->setImage('https://imgflip.com/s/meme/Cute-Cat.jpg');
            $manager->persist($user);

            for ($j = 0; $j < mt_rand(2, 5); $j++){
                $pet = new Pet();

                $pet->setUser($user)
                    ->setName($faker->name)
                    ->setContent($faker->sentence)
                    ->setPrice($faker->numberBetween(100, 100000))
                    ->setImage('https://imgflip.com/s/meme/Cute-Cat.jpg');

                $manager->persist($pet);

                for ($k = 0; $k < mt_rand(3, 8); $k++) {
                    $comment = new Comment();

                    $comment->setUser($user)
                        ->setPet($pet)
                        ->setContent($faker->sentence)
                        ->setCreatedAt($faker->dateTimeBetween('-6 months'));

                    $manager->persist($comment);
                }
            }
        }

        $manager->flush();
    }
}
