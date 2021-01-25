<?php


namespace App\Controller;

use App\Entity\Friends;
use App\Entity\User;
use App\Entity\Posts;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @param $homeCheck
     * @param $postCheck
     * @return Response
     */
    public function ToHome(){
        $loggedInUserId = $this->getUser();
        $homeCheck = $this->getDoctrine()->getRepository(Friends::class)->HomeCheck($loggedInUserId);
        $friendposts = [];
        foreach($homeCheck as $hCheck) {

            if($hCheck->getRecipient()->getId()!=$this->getUser()->getId()) {
                $User = $this->getDoctrine()->getRepository(User::class)->find($hCheck->getRecipient()->getId());

            }
            else{
                $User = $this->getDoctrine()->getRepository(User::class)->find($hCheck->getSender()->getId());

            }
            foreach($User->getPosts() as $post){
                $friendposts[]=$post;
            }
        }

        return $this->render('home.html.twig', [
            'friendPosts' => $friendposts
        ]);
    }
}