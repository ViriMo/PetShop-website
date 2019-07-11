<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CommentFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for($i = 1; $i <= 2; $i++)
        {
            $Comment = new Comment();
            $Comment->setUid('Viriya')
                ->setContent('I love that cat')
                ->setCreatedAt(new \DateTime())
                ->setImage("http://placehold.it/50x50")
                ->setPid(1);
            $manager->persist($Comment);
        }

        $manager->flush();
    }
}
