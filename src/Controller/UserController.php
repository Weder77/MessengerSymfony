<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
    public function register(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $manager = $this -> getDoctrine() -> getManager();
        $user = new User; // objet vide de l'entity Post
        
        // formulaire...
        $form = $this -> createForm(RegisterFormType::class, $user);

        // traitement des infos du formulaire
        $form -> handleRequest($request); // lier definitivement le $post aux infos du formulaire (recupere les donner en saisies en $_POST)

        if($form -> isSubmitted() && $form -> isValid() ){
            $manager -> persist($user); // enregistrer le post dans le systeme

            //  encodage du mot de passer
            $password = $user -> getPassword();
            $user -> setPassword($encoder->encodePassword($user, $password));

            $manager -> flush(); // execute toutes les requetes en attentes
            $this -> addFlash('success', 'Le compte à bien été créer !');

            return $this -> redirectToRoute('login');
        }
       

        return $this -> render('user/register.html.twig', array(
            'RegisterFormType' => $form -> createView()
        ));

        
    }
}
