<?php

namespace App\Entity;

use App\Repository\ChatsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ChatsRepository::class)
 */
class Chats
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="chats")
     * @ORM\JoinColumn(nullable=false)
     */
    private $User1;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $User2;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Text;

    /**
     * @ORM\Column(type="datetime")
     */
    private $Created;

    public function __construct(){
        $this->Created = new \DateTime('now');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser1(): ?User
    {
        return $this->User1;
    }

    public function setUser1(?User $User1): self
    {
        $this->User1 = $User1;

        return $this;
    }

    public function getUser2(): ?User
    {
        return $this->User2;
    }

    public function setUser2(?User $User2): self
    {
        $this->User2 = $User2;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->Text;
    }

    public function setText(string $Text): self
    {
        $this->Text = $Text;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->Created;
    }

    public function setCreated(\DateTimeInterface $Created): self
    {
        $this->Created = $Created;

        return $this;
    }
}
