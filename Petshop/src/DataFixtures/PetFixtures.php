<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\PostLike;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Pet;
use Faker;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PetFixtures extends Fixture
{
    /**
     * Encodeur de mot de passe
     *
     * @var UserPasswordEncoderInterface
     */
    private $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {

        $faker = Faker\Factory::create('fr_FR');
        $users = [];

        $user = new User();
        $user->setUsername('admin')
            ->setRoles(array('ROLE_ADMIN'))
            ->setPassword($this->encoder->encodePassword($user, 'password'))
            ->setImage('https://imgflip.com/s/meme/Cute-Cat.jpg');
        $manager->persist($user);
        $users[] = $user;


        for($i = 0; $i < 8; $i++) {
            $user = new User();
            $user->setUsername($faker->name)
                ->setRoles(array('ROLE_USER'))
                ->setPassword($this->encoder->encodePassword($user, 'password'))
                ->setImage('https://imgflip.com/s/meme/Cute-Cat.jpg');
            $manager->persist($user);
            $users[] = $user;
        }

        for ($j = 0; $j < mt_rand(15, 20); $j++){
            $pet = new Pet();

            $pet->setUser($faker->randomElement($users))
                ->setName($faker->name)
                ->setContent($faker->sentence)
                ->setPrice($faker->numberBetween(100, 100000))
                ->setImage('https://imgflip.com/s/meme/Cute-Cat.jpg');

            $manager->persist($pet);

            for ($k = 0; $k < mt_rand(12, 20); $k++) {
                $comment = new Comment();

                $comment->setUser($faker->randomElement($users))
                    ->setPet($pet)
                    ->setContent($faker->sentence)
                    ->setCreatedAt($faker->dateTimeBetween('-6 months'));

                $manager->persist($comment);

                for ($l = 0; $l < mt_rand(5, 10); $l++) {
                    $like = new PostLike();
                    $like->setComment($comment)
                        ->setUser($faker->randomElement($users));
                    $manager->persist($like);
                }

            }
        }

        $manager->flush();
    }
}
