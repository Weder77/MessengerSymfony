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
        $grp = new Group; 


        // formulaire
        $form = $this->createForm(CreateGroupType::class, $grp);
        
        return $this->render('conversation/creategroups.html.twig', array(
            'CreateGroupType' => $form->createView()
        ));

    }
}
