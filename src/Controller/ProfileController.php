<?php


namespace App\Controller;

use App\Form\ProfileType;
use App\Form\UserType;
use App\Entity\User;
use App\Entity\Friends;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile/{id}", name="profile_show", methods={"GET"})
     * @param User $user
     * @return Response
     */
    public function show(User $user): Response
    {
        return $this->render('profile/show.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * @Route("/edit/{id}", name="profile_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);

        /** @var UploadedFile $profilepicture */
        $profilepicture = $form->get('profilepicture')->getData();
        if ($profilepicture) {
            $originalFilename = pathinfo($profilepicture->getClientOriginalName(), PATHINFO_FILENAME);
            // this is needed to safely include the file name as part of the URL
            $newFilename = bin2hex(openssl_random_pseudo_bytes(16)) . '.' . $profilepicture->guessExtension();

            // Move the file to the directory where profile pictures are stored
            try {
                $profilepicture->move(
                    $this->getParameter('profilepicture_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }

            // updates the 'Profile picture Filename' property to store the image file name
            // instead of its contents
            $user->setProfilePicture($newFilename);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            if ($form->get('password')->getData() == $form->get('password_verify')->getData()) {

                $user->setPassword($passwordEncoder->encodePassword($user, $form->get('password')->getData()));
                $token = bin2hex(openssl_random_pseudo_bytes(16));
                $entityManager->persist($user);
                $entityManager->flush();

                return $this->redirectToRoute('home');
            }
        }

        return $this->render('profile/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("friends/accept/{id}", name="friendaccept", methods={"GET"})
     * @param Friends $id
     * @return Response
     */
    public function acceptRequest(Friends $id){
        $entityManager = $this->getDoctrine()->getManager();
        $id->setAcceptCheck(true);
        $id->setVisible(false);
        $entityManager->persist($id);
        $entityManager->flush();
        $this->addFlash("success", "You have accepted this friend request. You can now see your friends posts on the homepage.");
        return $this->redirectToRoute('home', [
        ]);
    }

    /**
     * @Route("friends/decline/{id}", name="frienddecline", methods={"GET"})
     * @param Friends $id
     * @return Response
     */
    public function declineRequest(Friends $id){
        $entityManager = $this->getDoctrine()->getManager();
        $id->setAcceptCheck(false);
        $id->setVisible(false);
        $entityManager->persist($id);
        $entityManager->flush();
        $this->addFlash("success", "You have declined this friend request. That user can no longer send you a friend request.");
        return $this->redirectToRoute('home', [
        ]);
    }

    /**
     * @Route("friends/inbox/{id}", name="friendinbox", methods={"GET"})
     * @param User $user
     * @return Response
     */
    public function showInbox(User $user): Response
    {

        return $this->render('profile/inbox.html.twig', [
            'user' => $user
        ]);
    }


}