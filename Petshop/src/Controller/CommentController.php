<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\PostLike;
use App\Repository\PostLikeRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    /**
     * @Route("/pet/{id}/like", name="com_likes")
     * @param Comment $comment
     * @param ObjectManager $manager
     * @param PostLikeRepository $likerepo
     * @return Response
     */
    public function like(Comment $comment, ObjectManager $manager, PostLikeRepository $likerepo) :Response
    {
        $user = $this->getUser();

        if(!$user) {
            return $this->json(['code' => 403, 'message' =>'Unauthorized'], 403);
        }

        if($comment->isLikedByUser($user)) {
            $like = $likerepo->findOneBy([
                'comment' => $comment,
                'user' => $user
            ]);

            $manager->remove($like);
            $manager->flush();

            return $this->json(['code' => 200,
                'message' => 'liked removed',
                'likes' => $likerepo->count(['comment' => $comment])],
                200);
        }

        $like = new PostLike();
        $like->setUser($user)
            ->setComment($comment);

        $manager->persist($like);
        $manager->flush();

        return $this->json(['code' => 200,
            'message' => 'like added',
            'likes' => $likerepo->count(['comment' => $comment])],
            200);
    }
}
