<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this -> redirectToRoute('login');
    }

    /**
     * @Route("/login", name="login")
     */
    public function login()
    {
        return $this->render('user/login.html.twig', array());
    }

    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request)
    {
        $manager = $this -> getDoctrine() -> getManager();
        $user = new User; // objet vide de l'entity Post
        
        // formulaire...
        $form = $this -> createForm(RegisterFormType::class, $user);

        // traitement des infos du formulaire
        $form -> handleRequest($request); // lier definitivement le $post aux infos du formulaire (recupere les donner en saisies en $_POST)

       
        return $this -> render('user/register.html.twig', array(
            'RegisterFormType' => $form -> createView()
        ));

        
    }
}
