<?php


namespace App\Controller;


use App\Entity\User;
use App\Entity\Friends;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FriendController extends AbstractController
{

    /**
     * @Route("friends", name="friends", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('friends/search.html.twig', [

        ]);
    }
    /**
     * @Route("friends/search", name="friendsearch", methods={"GET","POST"})
     * @param Request $request
     * @param $searchvalue
     */
    public function search(Request $request)
    {
        $input = $request->request->get('searchvalue');
        $results = $this->getDoctrine()->getRepository(User::class)->findUsersBySearch($input);

        return $this->render('friends/show.html.twig', [
            'results'=>$results
        ]);

    }

    /**
     * @Route("friends/request/{id}", name="friendrequest", methods={"GET","POST"})
     * @method("POST")
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function request(User $user)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $friends = new friends();
        $friends->setRecipient($user);
        $friends->setSender($this->getUser());
        $entityManager->persist($friends);
        $entityManager->flush();
        $this->addFlash("success", "You have sent them a friend request.");
        return $this->redirectToRoute('home', [
            'id' => $user->getID(),
        ]);
    }


}
