<?php


namespace App\Controller\Authentication;


use Exception;
use App\Entity\PasswordRecovery;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\Authentication\ClusterRepository;
use App\Repository\Authentication\PasswordRecoveryRepository;
use App\Repository\Authentication\UserRepository;
use App\Service\SecurityNotificationService;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Intl\Exception\NotImplementedException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;


/**
 * Class SecurityController
 * @package App\Controller
 * @Route("/user", name="security_")
 */
class SecurityController extends AbstractController
{

    private $mailer;


    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @Route("/login", name="login")
     * @param AuthenticationUtils $authenticationUtils
     * @param TranslatorInterface $translator
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils, TranslatorInterface $translator) {

        if($this->checkAuthentication($translator)) {
            // TODO Redirect to system dashboard
            // Please use return $this->render() to set a template.
            throw new NotImplementedException('Please specify a route for login');
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/authentication/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    /**
     * @Route("/register", name="register")
     * @param Request $request
     * @param TranslatorInterface $translator
     * @param ClusterRepository $clusterRepository
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param ValidatorInterface $validator
     * @param MailerInterface $mailer
     * @return Response
     */
    public function register(
        Request $request,
        TranslatorInterface $translator,
        ClusterRepository $clusterRepository,
        UserPasswordEncoderInterface $passwordEncoder,
        ValidatorInterface $validator,
        MailerInterface $mailer
    )
    {

        if ($this->checkAuthentication($translator)) {
            // TODO Redirect to system dashboard
            // Please use return $this->render() to set a template.
            throw new NotImplementedException('Please specify a route for login');
        }

        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        $errors = '';

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

            $errors = $validator->validate($user);

            $cluster = $clusterRepository->findOneBy(['name' => 'User']);
            $user->addCluster($cluster);

            if ($form->get('password')->getData() == $form->get('password_verify')->getData()) {

                $user->setPassword($passwordEncoder->encodePassword($user, $form->get('password')->getData()));
                $token = bin2hex(openssl_random_pseudo_bytes(16));


                $user->setActivationToken($token);
                $this->getDoctrine()->getManager()->persist($user);
                $this->getDoctrine()->getManager()->flush();

                /**
                 * Display & process form to request an account register.
                 *
                 * @Route("check_register", name="app_check_register")
                 * @param Request $request
                 * @param MailerInterface $mailer
                 * @return Response
                 */
                $email = (new TemplatedEmail())
                    ->from(new Address('pwmaintenancemediatastisch@gmail.com', 'Mediatastisch'))
                    ->to($user->getEmail())
                    ->subject('Your account authentication request')
                    ->htmlTemplate('security/authentication/email.html.twig')
                    ->context([
                        'activationToken' => $token,
                    ]);
                try {
                    $mailer->send($email);
                } catch (TransportExceptionInterface $e) {
                    $this->addFlash("failure", "Error in sending activation e-mail. Please try again.");
                }
                return ($this->render('security/authentication/check_register.html.twig'));

//            return new Response($this->render('security/check_register.html.twig', [
//                'registrationForm' => $form->createView(),
//            ]));


                //Email functie test end
            }

            $this->addFlash('danger', $translator->trans('Passwords do not match'));


        }
        return $this->render('security/authentication/register.html.twig', ['form' => $form->createView(), 'errors' => $errors]);
    }
    /**
     * @Route("/forgot-password", name="forgot_password")
     * @param TranslatorInterface $translator
     * @param UserRepository $userRepository
     * @param Request $request
     * @param SecurityNotificationService $notificationService
     * @return Response
     */
    public function forgot(
        TranslatorInterface $translator,
        UserRepository $userRepository,
        Request $request,
        SecurityNotificationService $notificationService
    ): Response
    {

        if($this->checkAuthentication($translator)) {
            // TODO Redirect to system dashboard
            // Please use return $this->render() to set a template.
            throw new NotImplementedException('Please specify a route for login');
            return $this->render('home.html.twig');
        }

        if($request->getMethod() == "POST") {
            $email = $request->get('email');

            $userCheck = $userRepository->findOneBy(['email' => $email]);
            if (!is_null($userCheck)) {
                $recovery = new PasswordRecovery();
                $recovery->setUser($userCheck);

                $this->getDoctrine()->getManager()->persist($recovery);
                $this->getDoctrine()->getManager()->flush();

                $notificationService->sendRecoveryMail($recovery);


            }

            $this->addFlash('success', $translator->trans('A recovery e-mail will be sent if it exists'));
        }

        return $this->render('security/authentication/forgot.html.twig');
    }

    /**
     * @Route("/recover-password/{token}", name="redeem_password")
     * @param $token
     * @param PasswordRecoveryRepository $recoveryRepository
     * @param Request $request
     * @param TranslatorInterface $translator
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return NotFoundHttpException|Response
     * @throws Exception
     */
    public function recover($token, PasswordRecoveryRepository $recoveryRepository, Request $request, TranslatorInterface $translator, UserPasswordEncoderInterface $passwordEncoder) {

        if($this->checkAuthentication($translator)) {
            // TODO Redirect to system dashboard
            // Please use return $this->render() to set a template.
            throw new NotImplementedException('Please specify a route for login');
        }

        $recovery = $recoveryRepository->findOneBy(['token' => $token, 'isUsed' => false]);

        if($recovery === null) {
            throw $this->createNotFoundException($translator->trans('Error 404 not found'));
        }

        if((new \DateTime('now')) > $recovery->getExpires()) {
            throw $this->createNotFoundException($translator->trans('Error 404 not found'));
        }

        if($request->getMethod() === "POST") {
            $newPassword = $request->get('new_password');
            $oldPassword = $request->get('repeat_password');

            if($newPassword == $oldPassword) {

                $recovery->getUser()->setPassword($passwordEncoder->encodePassword($recovery->getUser(), $newPassword));
                $recovery->setIsUsed(true);

                $this->getDoctrine()->getManager()->persist($recovery);
                $this->getDoctrine()->getManager()->flush();

                $this->addFlash('success', $translator->trans('The password has been changed!'));
                return $this->redirectToRoute('security_login');

            }

            $this->addFlash('danger', $translator->trans('The new passwords don\'t match!'));
        }


        return $this->render('security/authentication/recover-password.html.twig');
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout() {

    }

    private function checkAuthentication(TranslatorInterface $translator) : bool {
        // What if user is authenticated already
        if($this->getUser() != null) {
            $this->addFlash('primary', $translator->trans('You are already logged in'));
            return true;
        }

        return false;
    }
    /**
     * @Route("/activate/{token}", name="app_activate")
     */
    public function activate($token)
    {

        $repository = $this->getDoctrine()->getRepository(User::Class);
        $user = $repository->findOneBy(['ActivationToken' => $token]);
        if ($user != null) {
            $user->setActivationCheck(true);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->render('security/authentication/activate.html.twig');
        }
        else {
            $this->addFlash("failure", "Error; no account found. Please try again or contact the creators.");
            return $this->redirectToRoute('home');
        }
    }
}
