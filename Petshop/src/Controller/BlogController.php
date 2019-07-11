<?php

namespace App\Controller;

use App\Repository\CommentRepository;
use App\Entity\Comment;
use App\Repository\PetRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\CommentType;

class BlogController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        return $this->render('blog/index.html.twig', [
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
     * @Route("/login", name="login")
     */
    public function login()
    {
        return $this->render('petshop/login.html.twig');
    }

    /**
     * @Route("/pet", name="pet")
     */
    public function pet(PetRepository $repo)
    {
        $pet = $repo->findAll();
        return $this->render('petshop/pet.html.twig', [
            'pets' => $pet
        ]);
    }

    /**
     * @Route("/register", name="register")
     */
    public function register()
    {
        return $this->render('petshop/register.html.twig');
    }

    /**
     * @Route("/about", name="about")
     */
    public function about()
    {
        return $this->render('petshop/about.html.twig');
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
            return $this->redirectToRoute('pet_show', ['id' => $comment->getPid()]);
        }


        return $this->render('petshop/edit.html.twig', [
            "formComment" => $form->createView()
        ]);
    }


    /**
     * @Route("/pet/{id}", name="pet_show")
     */
    public function show(PetRepository $petrepo, CommentRepository $commentrepo, $id,
                         Request $request, ObjectManager $manager)
    {

        $pet = $petrepo->findOneBy(["pid" => $id]);
        $comment = $commentrepo->findBy(["pid" => $id], ["createdAt" => 'DESC']);

        $com = new Comment();


        $form = $this->createForm(CommentType::class, $com);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $com->setCreatedAt(new \DateTime());
            $com->setPid($id);
            $com->setImage('http://placehold.it/50x50');
            $manager->persist($com);
            $manager->flush();
            return $this->redirectToRoute('pet_show', ['id' => $com->getPid()]);
        }

        return $this->render('petshop/show.html.twig', [
            "comments" => $comment,
            "pet" => $pet,
            "formComment" => $form->createView()
            ]);
    }
}
