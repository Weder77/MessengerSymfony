<?php

namespace App\Controller;

use App\Entity\Group;
use App\Entity\Message;
use App\Form\CreateGroupType;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Form\MessageType;
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
    public function groupsConversation($id, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $rep = $this->getDoctrine()->getRepository(User::class);

        $id_user = $this->getUser()->getId();
        $groups = $rep->find($id_user)->getGroups();

        $rep = $this->getDoctrine()->getRepository(Group::class);
        $group = $rep->find($id);

        $message = new Message;
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        // FORM MESSAGE
        if ($form->isSubmitted() && $form->isValid()) {

            $manager->persist($message); // enregistrer le post dans le systeme

            $message->setGroupSend($group);
            $message->setUser($this->getUser());
            $message->setDate(new \DateTime('now'));
            $message->setState(3);

            $manager->flush();
            return $this -> redirectToRoute('group-conversation', [
                'id' => $id
            ]);
        }

        return $this->render('conversation/groups.html.twig', array(
            'MessageType' => $form->createView(),
            'groups' => $groups,
            'actualGroup' => $group,
        ));
    }

    /**
     * @Route("/groups/add", name="groups-create")
     */
    public function createGroups(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(User::class);
        $users = $repository->findAll();

        $manager = $this->getDoctrine()->getManager();
        $group = new Group;

        // CREATE GROUP FORM
        $form = $this->createForm(CreateGroupType::class, $group);
        $form->handleRequest($request); // lier definitivement le $post aux infos du formulaire (recupere les données en saisies en $_POST)

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($group);

            $group->setDate(new \DateTime('now'));

            $user_a = $this->getUser();

            $group->setUsersAdmin($user_a);
            $group->addUser($user_a);

            foreach ($group->getUsers() as $user) {
                if ($user != $user_a) {
                    $group->addUser($user);
                }
            }

            $manager->flush();
            $this->addFlash('success', 'Your group has been created!');

            return $this->redirectToRoute('groups-list');
        }

        return $this->render('conversation/creategroups.html.twig', array(
            'CreateGroupType' => $form->createView(),
            'users' => $users
        ));
    }
}
