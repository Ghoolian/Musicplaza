<?php


namespace App\Service;


use App\Entity\PasswordRecovery;
use Doctrine\ORM\EntityManagerInterface;
use Swift_Message;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Translation\Translator;

class SecurityNotificationService
{
    /**
     * @var EntityManagerInterface
     */
    private $_entityManager;
    /**
     * @var \Swift_Mailer
     */
    private $_mailer;
    /**
     * @var ContainerInterface
     */
    private $_container;
    /**
     * @var Translator
     */
    private $_translator;

    /**
     * NotificationService constructor.
     * @param \Swift_Mailer $mailer
     * @param ContainerInterface $container
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(\Swift_Mailer $mailer, ContainerInterface $container, EntityManagerInterface $entityManager)
    {
        $this->_mailer = $mailer;
        $this->_container = $container;
        $this->_entityManager = $entityManager;
    }

    public function sendRecoveryMail(PasswordRecovery $recovery) {
        $email = (new Swift_Message)
            ->setSubject('Password recovery')
            ->setFrom($_ENV['MAIL_SENDER'], 'ProjectName password recovery')
            ->setTo($recovery->getUser()->getEmail());



        try {
            $email->setBody($this->_container->get('twig')->render('email/authentication/forgot-password.html.twig', [
                'name' => $recovery->getUser()->getName(),
                'email' => $recovery->getUser()->getEmail(),
                'token' => $recovery->getToken(),
            ]), 'text/html');
        } catch (\Twig_Error_Loader $e) {
            dump($e);
        } catch (\Twig_Error_Runtime $e) {
            dump($e);
        } catch (\Twig_Error_Syntax $e) {
            dump($e);
        }


        $this->_mailer->send($email);
    }
}
