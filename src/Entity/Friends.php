<?php

namespace App\Entity;

use App\Repository\FriendsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FriendsRepository::class)
 */
class Friends
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $AcceptCheck;

    /**
     * @ORM\Column(type="boolean")
     */
    private $Visible;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="recipient", fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Recipient;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="sender", fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Sender;


    public function __construct(){
        $this->AcceptCheck = false;
        $this->Visible = true;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAcceptCheck(): ?bool
    {
        return $this->AcceptCheck;
    }

    public function setAcceptCheck(bool $AcceptCheck): self
    {
        $this->AcceptCheck = $AcceptCheck;

        return $this;
    }

    public function getVisible(): ?bool
    {
        return $this->Visible;
    }

    public function setVisible(bool $Visible): self
    {
        $this->Visible = $Visible;

        return $this;
    }

    public function setRecipient(?User $recipient): self
    {
        $this->Recipient = $recipient;

        return $this;
    }

    public function getRecipient(): ?User
    {
        return $this->Recipient;
    }

    public function getSender(): ?User
    {
        return $this->Sender;
    }

    public function setSender(?User $sender): self
    {
        $this->Sender = $sender;

        return $this;
    }




}
