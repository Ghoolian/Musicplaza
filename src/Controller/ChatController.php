<?php


namespace App\Controller;

use App\Entity\Friends;
use App\Form\ChatType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Chats;
use App\Entity\User;

class ChatController extends AbstractController
{

    /**
     * @Route("/Chats/chat/{id}", name="chat_show", methods={"GET", "POST"})
     * @param User $user
     * @param Chats $chats
     * @return Response
     */
    public function show(User $user, Request $request): Response
    {
        $chats = new Chats();
        $chats->setUser1($user);
        $chats->setUser2($this->getUser());
        $form = $this->createForm(ChatType::class, $chats);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($chats);
            $entityManager->flush();

            return $this->redirectToRoute('chat_show', [
                'id' => $user->getID()
            ]);
        }

        $user1 = $this->getUser();
        $user2 = $user;
        $chatCheck = $this->getDoctrine()->getRepository(Chats::class)->ChatCheck($user1, $user2);

        return $this->render('chats/chat.html.twig', [
            'chats' => $chats,
            'chatmessages' => $chatCheck,
            'form' => $form->createView(),
        ]);
    }

}