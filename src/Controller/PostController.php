<?php


namespace App\Controller;

use App\Entity\Posts;
use App\Entity\Replies;
use App\Form\PostType;
use App\Form\RepliesType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{


    /**
     * @Route("post/{id}", name="posts_show", methods={"GET"})
     * @param Posts $posts
     * @return Response
     */
    public function show(Posts $posts): Response
    {
        return $this->render('posts/show.html.twig', [
            'posts' => $posts
        ]);
    }
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
     * @Route("/posts/{id}/newreply/", name="replies_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function newReply(Request $request, Posts $posts): Response
    {
        $replies = new replies();
        $replies->setPost($posts);
        $replies->setUser($this->getUser());
        $form = $this->createForm(RepliesType::class, $replies);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($replies);
            $entityManager->flush();

            return $this->redirectToRoute('posts_show', [
                'id' => $posts->getID(),
            ]);
        }
        return $this->render('replies/new.html.twig', [
            'replies' => $replies,

            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/{id}/edit", name="posts_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Posts $posts
     * @return Response
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