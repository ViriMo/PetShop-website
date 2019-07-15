<?php

namespace App\Controller;



use App\Repository\CommentRepository;
use App\Entity\Pet;
use App\Entity\Comment;
use App\Repository\PetRepository;
use Doctrine\Common\Persistence\ObjectManager;
use http\Env\Response;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\CommentType;
use App\Form\PetType;

class BlogController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        return $this->render('petshop/index.html.twig', [
            'controller_name' => 'BlogController',
        ]);
    }

    /**
     * @Route("/home", name="home")
     */
    public function home()
    {
        return $this->render('petshop/home.html.twig');
    }

    /**
     * @Route("/pet", name="pet")
     */
    public function pet(PetRepository $repo, Request $request, PaginatorInterface $paginator)
    {
        $pet = $repo->findby([], ["id" => 'DESC']);
        $pagination = $paginator->paginate(
            $pet, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            5 /*limit per page*/
        );
        return $this->render('petshop/pet.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * @Route("/about", name="about")
     */
    public function about()
    {
        return $this->render('petshop/about.html.twig');
    }

    /**
     * @Route("/sell", name="sell")
     */
    public function sell(Pet $pet = null, Request $request, ObjectManager $manager)
    {
        if(!$pet) {
            $pet = new Pet();
        }
        $form = $this->createForm(PetType::class, $pet);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            if($this->getUser()) {
                $pet->setUser($this->getUser()); //should always happen
            }
                $manager->persist($pet);
                $manager->flush();
                return $this->redirectToRoute('sell');
        }

        return $this->render('petshop/sell.html.twig', [
            'formPet' => $form->createView()
        ]);
    }

    /**
     * @Route("/fun", name="fun")
     */
    public function fun()
    {
        return $this->render('petshop/fun.html.twig');
    }



    /**
     * @Route("/pet/edit/{id}", name="edit")
     */
    public function edit($id, CommentRepository $commentrepo, Request $request, ObjectManager $manager)
    {
        $comment = $commentrepo->findOneBy(["id" => $id]);

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($comment);
            $manager->flush();
            return $this->redirectToRoute('pet_show', ['id' => $comment->getPet()->getId()]);
        }


        return $this->render('petshop/edit.html.twig', [
            "formComment" => $form->createView()
        ]);
    }


    /**
     * @Route("/pet/{id}", name="pet_show")
     */
    public function show(PetRepository $petrepo, $id, Request $request, ObjectManager $manager,
                         PaginatorInterface $paginator)
    {
        $pet = $petrepo->findOneBy(["id" => $id]);
        if(!$pet) {
            return $this->redirectToRoute('pet');
        }

        $comRep = $this->getDoctrine()->getRepository(Comment::class);
        $comment = $comRep->findBy(
            ['pet' => $pet],
            ['createdAt' => 'DESC']
        );

        $com = new Comment();
        $form = $this->createForm(CommentType::class, $com);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            if($this->getUser()) {
                $com->setUser($this->getUser()); //should always happen
            }
            $com->setPet($pet);
            $com->setCreatedAt(new \DateTime());
            $manager->persist($com);
            $manager->flush();
            return $this->redirectToRoute('pet_show', ['id' => $pet->getId()]);
        }



        $pagination = $paginator->paginate(
            $comment, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            5 /*limit per page*/
        );

        return $this->render('petshop/show.html.twig', [
            "pet" => $pet,
            "pagination" => $pagination,
            "formComment" => $form->createView()
            ]);
    }
}
