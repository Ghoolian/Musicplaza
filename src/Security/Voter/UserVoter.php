<?php

namespace App\Security\Voter;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class UserVoter extends Voter
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;
    /**
     * @var TokenStorageInterface
     */
    protected $tokenStorage;
    /**
     * @var RequestStack
     */
    protected $requestStack;

    protected $_userPermissions = [];

    /**
     * UsersVoter constructor.
     * @param EntityManagerInterface $entityManager
     * @param TokenStorageInterface $tokenStorage
     * @param RequestStack $requestStack
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        TokenStorageInterface $tokenStorage,
        RequestStack $requestStack
    ) {

        $this->entityManager = $entityManager;
        $this->tokenStorage = $tokenStorage;
        $this->requestStack = $requestStack;
    }

    /**
     * Determines if the attribute and subject are supported by this voter.
     *
     * @param string $attribute An attribute
     * @param mixed $subject The subject to secure, e.g. an object the user wants to access or any other PHP type
     *
     * @return bool True if the attribute and subject are supported, false otherwise
     */
    protected function supports($attribute, $subject)
    {

        if($this->isLoggedIn()) {
            // This is for every granted method that is fired so it supports everything.
            $this->getUserPermissions();

            return true;
        }

        return false;
    }

    /**
     * Perform a single access check operation on a given attribute, subject and token.
     * It is safe to assume that $attribute and $subject already passed the "supports()" method check.
     *
     * @param string $attribute
     * @param mixed $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        // Actually check if the user has the permission.
        return (in_array($attribute, $this->_userPermissions));
    }

    /**
     * @return void
     */
    protected function getUserPermissions() : void {

        if($this->isLoggedIn()) {
            $permissions = $this->entityManager
                ->getRepository(User::class)
                ->withPermissions($this->tokenStorage->getToken()->getUser()->getId());

            foreach($permissions as $permission) {
                array_push($this->_userPermissions, $permission['name']);
            }
        }
    }

    /**
     * @return bool
     */
    private function isLoggedIn() {
        return ($this->tokenStorage->getToken()->getUser() == "anon." || $this->tokenStorage->getToken()->getUser() == null) ? false : true;
    }
}
