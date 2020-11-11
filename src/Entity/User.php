<?php

namespace App\Entity;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Authentication\UserRepository")
 * @UniqueEntity("email")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deleted;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $is_super;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Cluster", inversedBy="users")
     */
    private $clusters;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PasswordRecovery", mappedBy="user", orphanRemoval=true)
     */
    private $passwordRecoveries;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $profilepicture;

    /**
     * @ORM\Column(type="string", length=255, nullable=true))
     */
    private $gender;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $birthday;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ActivationToken;

    /**
     * @ORM\Column(type="boolean")
     */
    private $ActivationCheck;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Playstation;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Nintendo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Xbox;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Twitter;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Discord;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Facebook;

    /**
     * @ORM\OneToMany(targetEntity=Posts::class, mappedBy="User", orphanRemoval=true)
     * @ORM\OrderBy({"created" = "DESC"})
     */
    private $posts;

    /**
     * @ORM\OneToMany(targetEntity=Replies::class, mappedBy="user", orphanRemoval=true)
     */
    private $replies;


    public function __construct()
    {
        $this->created = new DateTime('now');
        $this->updated = new DateTime('now');
        $this->is_super = false;
        $this->clusters = new ArrayCollection();
        $this->passwordRecoveries = new ArrayCollection();
        $this->ActivationCheck = "0";
        $this->posts = new ArrayCollection();
        $this->replies = new ArrayCollection();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        if(false == empty($password)) {
            $this->password = $password;
        }

        return $this;
    }

    public function getCreated(): ?DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getDeleted(): ?DateTimeInterface
    {
        return $this->deleted;
    }

    public function setDeleted(?DateTimeInterface $deleted): self
    {
        $this->deleted = $deleted;

        return $this;
    }

    public function getUpdated(): ?DateTimeInterface
    {
        return $this->updated;
    }

    public function setUpdated(DateTimeInterface $updated): self
    {
        $this->updated = $updated;

        return $this;
    }

    public function getIsSuper(): ?bool
    {
        return $this->is_super;
    }

    public function setIsSuper(?bool $is_super): self
    {
        $this->is_super = $is_super;

        return $this;
    }

    /**
     * @return Collection|Cluster[]
     */
    public function getClusters(): Collection
    {
        return $this->clusters;
    }

    public function addCluster(Cluster $cluster): self
    {
        if (!$this->clusters->contains($cluster)) {
            $this->clusters[] = $cluster;
        }

        return $this;
    }

    public function removeCluster(Cluster $cluster): self
    {
        if ($this->clusters->contains($cluster)) {
            $this->clusters->removeElement($cluster);
        }

        return $this;
    }

    /**
     * Returns the roles granted to the user.
     *
     *     public function getRoles()
     *     {
     *         return ['ROLE_USER'];
     *     }
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles()
    {
        // We don't use text based roles
        // But because Symfony occupies the name 'Role'
        // We use 'clusters'
        return ['ROLE_USER'];
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        return $this->getName();
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }


    /**
     * @return Collection|PasswordRecovery[]
     */
    public function getPasswordRecoveries(): Collection
    {
        return $this->passwordRecoveries;
    }

    public function addPasswordRecovery(PasswordRecovery $passwordRecovery): self
    {
        if (!$this->passwordRecoveries->contains($passwordRecovery)) {
            $this->passwordRecoveries[] = $passwordRecovery;
            $passwordRecovery->setUser($this);
        }

        return $this;
    }

    public function removePasswordRecovery(PasswordRecovery $passwordRecovery): self
    {
        if ($this->passwordRecoveries->contains($passwordRecovery)) {
            $this->passwordRecoveries->removeElement($passwordRecovery);
            // set the owning side to null (unless already changed)
            if ($passwordRecovery->getUser() === $this) {
                $passwordRecovery->setUser(null);
            }
        }

        return $this;
    }
    public function getProfilepicture(): ?string
    {
        return $this->profilepicture;
    }

    public function setProfilepicture(string $Profilepicture): self
    {
        $this->profilepicture = $Profilepicture;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getBirthday(): ?DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(?DateTimeInterface $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getActivationToken(): ?string
    {
        return $this->ActivationToken;
    }

    public function setActivationToken(string $ActivationToken): self
    {
        $this->ActivationToken = $ActivationToken;

        return $this;
    }

    public function getActivationCheck(): ?bool
    {
        return $this->ActivationCheck;
    }

    public function setActivationCheck(bool $ActivationCheck): self
    {
        $this->ActivationCheck = $ActivationCheck;

        return $this;
    }

    public function getPlaystation(): ?string
    {
        return $this->Playstation;
    }

    public function setPlaystation(?string $Playstation): self
    {
        $this->Playstation = $Playstation;

        return $this;
    }

    public function getNintendo(): ?string
    {
        return $this->Nintendo;
    }

    public function setNintendo(?string $Nintendo): self
    {
        $this->Nintendo = $Nintendo;

        return $this;
    }

    public function getXbox(): ?string
    {
        return $this->Xbox;
    }

    public function setXbox(?string $Xbox): self
    {
        $this->Xbox = $Xbox;

        return $this;
    }

    public function getTwitter(): ?string
    {
        return $this->Twitter;
    }

    public function setTwitter(?string $Twitter): self
    {
        $this->Twitter = $Twitter;

        return $this;
    }

    public function getDiscord(): ?string
    {
        return $this->Discord;
    }

    public function setDiscord(?string $Discord): self
    {
        $this->Discord = $Discord;

        return $this;
    }

    public function getFacebook(): ?string
    {
        return $this->Facebook;
    }

    public function setFacebook(?string $Facebook): self
    {
        $this->Facebook = $Facebook;

        return $this;
    }

    /**
     * @return Collection|Posts[]
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Posts $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setUser($this);
        }

        return $this;
    }

    public function removePost(Posts $post): self
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getUser() === $this) {
                $post->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Replies[]
     */
    public function getReplies(): Collection
    {
        return $this->replies;
    }

    public function addReply(Replies $reply): self
    {
        if (!$this->replies->contains($reply)) {
            $this->replies[] = $reply;
            $reply->setUser($this);
        }

        return $this;
    }

    public function removeReply(Replies $reply): self
    {
        if ($this->replies->removeElement($reply)) {
            // set the owning side to null (unless already changed)
            if ($reply->getUser() === $this) {
                $reply->setUser(null);
            }
        }

        return $this;
    }
}
