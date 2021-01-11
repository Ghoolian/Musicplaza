<?php


namespace App\Controller;

use App\Entity\Chats;
use App\Entity\User;
use App\Entity\Friends;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ChatController extends AbstractController
{
    /**
     * @Route("/Chat/{id}", name="chats_show", methods={"GET"})
     * @param Chats $chats
     * @return Response
     */
    public function show(Chats $chats): Response
    {

        return $this->render('chats/chat.html.twig', [
            'chats' => $chats
        ]);
    }

    /**
     * @Route("/Chats/chat/{chat_id}", name="chatrequest", methods={"GET","POST"})
     * @method("POST")
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function chatrequest(User $user)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $chats = new Chats();
        $chats->setUser1($user);
        $chats->setUser2($this->getUser());
        $entityManager->persist($chats);
        $entityManager->flush();
        $this->addFlash("success", "You have started a chat!");
        return $this->redirectToRoute('chatrequest', [
            'id' => $chats->getID(),
        ]);
    }
}