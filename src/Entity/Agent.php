<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AgentRepository")
 * @UniqueEntity(fields={"Email"},message="L'email existe déjà dans la base de données")
 * @UniqueEntity(fields={"UserName"},message="Le pseudonyme existe déjà dans la base donnéés")
 * 
 */
class Agent implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $FirstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $LastName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Phone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Fax;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email(message = "l'email '{{ value }}' n'est pas valide.",checkMX = true)
     * 
     */
    private $Email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min="6", minMessage="Votre pseudonyme doit faire minimun {{ limit }} caractères")
     */
    private $UserName;

    /**
     * @ORM\Column(type="string", length=300)
     * @Assert\Length(min="8", minMessage="Votre mot de passe doit faire minimun {{ limit }} caractères")
     */
    private $Password;

    /**
     * @Assert\EqualTo(propertyPath="Password", message="Vous n'avez pas entré le même mot de passe")
     */
    private $Confirme_password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Picture;

    /**
     * @ORM\Column(type="boolean")
     */
    private $IsAdmin;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $IsOnline;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ListSize;

    /**
     * @ORM\Column(type="boolean")
     */
    private $IsActivated;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $IPAddress;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Role", inversedBy="agents")
     */
    private $Roles;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ActionTracker", mappedBy="Agent")
     */
    private $actionTrackers;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Client", mappedBy="agent")
     */
    private $Clients;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\QuoteOrder", mappedBy="Agent")
     */
    private $orders;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Message", mappedBy="Agent")
     */
    private $messages;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Discussion", inversedBy="agents")
     */
    private $Discussion;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="agent")
     */
    private $Comment;

    public function __construct()
    {
        $this->Roles = new ArrayCollection();
        $this->actionTrackers = new ArrayCollection();
        $this->Clients = new ArrayCollection();
        $this->orders = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->Discussion = new ArrayCollection();
        $this->Comment = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->FirstName;
    }

    public function setFirstName(string $FirstName): self
    {
        $this->FirstName = $FirstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->LastName;
    }

    public function setLastName(string $LastName): self
    {
        $this->LastName = $LastName;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->Phone;
    }

    public function setPhone(?string $Phone): self
    {
        $this->Phone = $Phone;

        return $this;
    }

    public function getFax(): ?string
    {
        return $this->Fax;
    }

    public function setFax(?string $Fax): self
    {
        $this->Fax = $Fax;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->Email;
    }

    public function setEmail(string $Email): self
    {
        $this->Email = $Email;

        return $this;
    }

    public function getUserName(): ?string
    {
        return $this->UserName;
    }

    public function setUserName(string $UserName): self
    {
        $this->UserName = $UserName;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->Password;
    }

    public function setPassword(string $Password): self
    {
        $this->Password = $Password;

        return $this;
    }

    public function getConfirmePassword(): ?string
    {
        return $this->Confirme_password;
    }

    public function setConfirmePassword(string $Confirme_password): self
    {
        $this->Confirme_password = $Confirme_password;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->Picture;
    }

    public function setPicture(?string $Picture): self
    {
        $this->Picture = $Picture;

        return $this;
    }

    public function getIsAdmin(): ?bool
    {
        return $this->IsAdmin;
    }

    public function setIsAdmin(bool $IsAdmin): self
    {
        $this->IsAdmin = $IsAdmin;

        return $this;
    }

    public function getIsOnline(): ?bool
    {
        return $this->IsOnline;
    }

    public function setIsOnline(?bool $IsOnline): self
    {
        $this->IsOnline = $IsOnline;

        return $this;
    }

    public function getListSize(): ?int
    {
        return $this->ListSize;
    }

    public function setListSize(?int $ListSize): self
    {
        $this->ListSize = $ListSize;

        return $this;
    }

    public function getIsActivated(): ?bool
    {
        return $this->IsActivated;
    }

    public function setIsActivated(bool $IsActivated): self
    {
        $this->IsActivated = $IsActivated;

        return $this;
    }

    public function getIPAddress(): ?string
    {
        return $this->IPAddress;
    }

    public function setIPAddress(?string $IPAddress): self
    {
        $this->IPAddress = $IPAddress;

        return $this;
    }

    /**
     * @return Collection|Role[]
     */
    /*public function getRoles(): Collection
    {
        return $this->Roles;
    }*/
    public function getRoles()
    {
        return ["USER_ROLE"];
    }

    public function addRole(Role $role): self
    {
        if (!$this->Roles->contains($role)) {
            $this->Roles[] = $role;
        }

        return $this;
    }

    public function removeRole(Role $role): self
    {
        if ($this->Roles->contains($role)) {
            $this->Roles->removeElement($role);
        }

        return $this;
    }

    /**
     * @return Collection|ActionTracker[]
     */
    public function getActionTrackers(): Collection
    {
        return $this->actionTrackers;
    }

    public function addActionTracker(ActionTracker $actionTracker): self
    {
        if (!$this->actionTrackers->contains($actionTracker)) {
            $this->actionTrackers[] = $actionTracker;
            $actionTracker->setAgent($this);
        }

        return $this;
    }

    public function removeActionTracker(ActionTracker $actionTracker): self
    {
        if ($this->actionTrackers->contains($actionTracker)) {
            $this->actionTrackers->removeElement($actionTracker);
            // set the owning side to null (unless already changed)
            if ($actionTracker->getAgent() === $this) {
                $actionTracker->setAgent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Client[]
     */
    public function getClients(): Collection
    {
        return $this->Clients;
    }

    public function addClient(Client $client): self
    {
        if (!$this->Clients->contains($client)) {
            $this->Clients[] = $client;
            $client->setAgent($this);
        }

        return $this;
    }

    public function removeClient(Client $client): self
    {
        if ($this->Clients->contains($client)) {
            $this->Clients->removeElement($client);
            // set the owning side to null (unless already changed)
            if ($client->getAgent() === $this) {
                $client->setAgent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|QuoteOrder[]
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(QuoteOrder $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setAgent($this);
        }

        return $this;
    }

    public function removeOrder(QuoteOrder $order): self
    {
        if ($this->orders->contains($order)) {
            $this->orders->removeElement($order);
            // set the owning side to null (unless already changed)
            if ($order->getAgent() === $this) {
                $order->setAgent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->addAgent($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->contains($message)) {
            $this->messages->removeElement($message);
            $message->removeAgent($this);
        }

        return $this;
    }

    /**
     * @return Collection|Discussion[]
     */
    public function getDiscussion(): Collection
    {
        return $this->Discussion;
    }

    public function addDiscussion(Discussion $discussion): self
    {
        if (!$this->Discussion->contains($discussion)) {
            $this->Discussion[] = $discussion;
        }

        return $this;
    }

    public function removeDiscussion(Discussion $discussion): self
    {
        if ($this->Discussion->contains($discussion)) {
            $this->Discussion->removeElement($discussion);
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComment(): Collection
    {
        return $this->Comment;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->Comment->contains($comment)) {
            $this->Comment[] = $comment;
            $comment->setAgent($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->Comment->contains($comment)) {
            $this->Comment->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getAgent() === $this) {
                $comment->setAgent(null);
            }
        }

        return $this;
    }

    public function getSalt(){}

    public function eraseCredentials(){}

}
