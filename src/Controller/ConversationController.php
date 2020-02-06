<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ConversationController extends AbstractController
{
    /**
     * @Route("/groups", name="groups")
     */
    public function groups()
    {
        return $this->render('conversation/groups.html.twig', array());
    }
}
