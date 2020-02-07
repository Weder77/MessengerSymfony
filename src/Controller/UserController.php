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
        return $this->redirectToRoute('login');
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
        $manager = $this->getDoctrine()->getManager();
        $user = new User;

        // REGISTER FORM
        $form = $this->createForm(RegisterFormType::class, $user);
        $form->handleRequest($request); // lier definitivement le $post aux infos du formulaire (recupere les données en saisies en $_POST)

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($user);

            if($user -> getFile()){
                $user-> uploadFile();
            }

            //  encodage du mot de passer
            $password = $user->getPassword();
            $user->setPassword($encoder->encodePassword($user, $password));

            $manager->flush();
            $this->addFlash('success', 'Le compte à bien été créer, vous pouvez maintenant vous connecter !');

            return $this->redirectToRoute('login');
        }


        return $this->render('user/register.html.twig', array(
            'RegisterFormType' => $form->createView()
        ));
    }

    /**
     * route nécessaire pour le fonctionnement de sécurité de ma connexion
     * @Route("/login_check", name="login_check")
     */
    public function loginCheck()
    {
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
        return $this->redirectToRoute('login');
    }
}
