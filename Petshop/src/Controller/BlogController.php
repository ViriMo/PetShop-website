<?php

namespace App\Controller;

use App\Repository\CommentRepository;
use App\Repository\PetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

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
     * @Route("/pet/{id}", name="pet_show")
     */
    public function show(PetRepository $petrepo, CommentRepository $commentrepo, $id)
    {
        $pet = $petrepo->findOneBy(["pid" => $id]);
        $comment = $commentrepo->findBy(["id" => $id]);
        return $this->render('petshop/show.html.twig', [
            "comments" => $comment,
            "pet" => $pet
            ]);
    }
}
