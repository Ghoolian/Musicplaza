<?php


namespace App\Controller;

use App\Entity\Posts;
use App\Form\PostType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PostController extends AbstractController
{

    /**
     * @Route("/new", name="posts_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $posts = new Posts();
        $posts->setUser($this->getUser());
        $form = $this->createForm(PostType::class, $posts);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($posts);
            $entityManager->flush();

            return $this->redirectToRoute('profile_show', [
                'id' => $this->getUser()->getID()
            ]);
        }

        return $this->render('posts/new.html.twig', [
            'posts' => $posts,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/{id}/edit", name="posts_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Posts $posts): Response
    {
        $form = $this->createForm(PostType::class, $posts);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($posts);
                $entityManager->flush();

                return $this->redirectToRoute('home');
            }

        return $this->render('posts/edit.html.twig', [
            'posts' => $posts,
            'form' => $form->createView(),
        ]);
    }
}