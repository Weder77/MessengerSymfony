<?php

namespace App\Controller;

use App\Entity\Group;
use App\Form\CreateGroupType;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ConversationController extends AbstractController
{
    /**
     * @Route("/groups/", name="groups-list")
     */
    public function groupsList()
    {
        $rep = $this->getDoctrine()->getRepository(User::class);
        $id = $this->getUser()->getId();
        $groups = $rep->find($id)->getGroups();

        return $this->render('conversation/groupsList.html.twig', array(
            'groups' => $groups,
        ));
    }

    /**
     * @Route("/groups/show/{id}", name="group-conversation")
     */
    public function groupsConversation($id)
    {
        $rep = $this->getDoctrine()->getRepository(User::class);
        $id_user = $this->getUser()->getId();
        $groups = $rep->find($id_user)->getGroups();
        
        $rep = $this->getDoctrine()->getRepository(Group::class);
        $group = $rep->find($id);

        return $this->render('conversation/groups.html.twig', array(
            'groups' => $groups,
            'actualGroup' => $group,
        ));
    }

    /**
     * @Route("/groups/add", name="groups-create")
     */
    public function createGroups(Request $request)
    {
        $manager = $this -> getDoctrine() -> getManager();
        $grp = new Group; 

        // formulaire
        $form = $this->createForm(CreateGroupType::class, $grp);
        
        // traitement des infos du formulaire
        $form->handleRequest($request); // lier definitivement le $post aux infos du formulaire (recupere les donner en saisies en $_POST)

        if ($form->isSubmitted() && $form->isValid()) {

            $manager->persist($grp); // enregistrer le post dans le systeme

            $grp->setDate(new \DateTime('now'));

            $user_a = $this->getUser();
            
            $grp->setUsersAdmin($user_a);

            $grp->addUser($user_a);
            

            $manager->flush(); // execute toutes les requetes en attentes
            $this->addFlash('success', 'Le groupe a bien été crée ! Vous le retrouverez à l\'accueil !');

        }

        return $this->render('conversation/creategroups.html.twig', array(
            'CreateGroupType' => $form->createView()
        ));

    }
}
