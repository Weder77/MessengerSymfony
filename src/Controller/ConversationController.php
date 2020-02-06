<?php

namespace App\Controller;

use App\Entity\Group;
use App\Form\CreateGroupType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ConversationController extends AbstractController
{
    /**
     * @Route("/groups", name="groups")
     */
    public function groups()
    {
        return $this->render('conversation/groups.html.twig', array());
    }

    /**
     * @Route("/creategroups", name="creategroups")
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
