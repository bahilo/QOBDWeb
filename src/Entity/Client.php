<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ClientRepository")
 */
class Client
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
    private $CompanyName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Phone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Fax;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Rib;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $CRN;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $PayDelay;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="client")
     */
    private $Comment;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $MaxCredit;

    /**
     * @ORM\Column(type="boolean")
     */
    private $IsActivated;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Address", mappedBy="client")
     */
    private $Addresses;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Agent", inversedBy="Clients")
     */
    private $agent;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\QuoteOrder", mappedBy="Client")
     */
    private $orders;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Bill", inversedBy="Client")
     */
    private $bill;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Contact", mappedBy="Client")
     */
    private $contacts;

    public function __construct()
    {
        $this->Comment = new ArrayCollection();
        $this->Addresses = new ArrayCollection();
        $this->orders = new ArrayCollection();
        $this->contacts = new ArrayCollection();
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

    public function getCompanyName(): ?string
    {
        return $this->CompanyName;
    }

    public function setCompanyName(?string $CompanyName): self
    {
        $this->CompanyName = $CompanyName;

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

    public function getRib(): ?string
    {
        return $this->Rib;
    }

    public function setRib(?string $Rib): self
    {
        $this->Rib = $Rib;

        return $this;
    }

    public function getCRN(): ?string
    {
        return $this->CRN;
    }

    public function setCRN(?string $CRN): self
    {
        $this->CRN = $CRN;

        return $this;
    }

    public function getPayDelay(): ?int
    {
        return $this->PayDelay;
    }

    public function setPayDelay(?int $PayDelay): self
    {
        $this->PayDelay = $PayDelay;

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
            $comment->setClient($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->Comment->contains($comment)) {
            $this->Comment->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getClient() === $this) {
                $comment->setClient(null);
            }
        }

        return $this;
    }

    public function getMaxCredit(): ?float
    {
        return $this->MaxCredit;
    }

    public function setMaxCredit(?float $MaxCredit): self
    {
        $this->MaxCredit = $MaxCredit;

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

    /**
     * @return Collection|Address[]
     */
    public function getAddresses(): Collection
    {
        return $this->Addresses;
    }

    public function addAddress(Address $address): self
    {
        if (!$this->Addresses->contains($address)) {
            $this->Addresses[] = $address;
            $address->setClient($this);
        }

        return $this;
    }

    public function removeAddress(Address $address): self
    {
        if ($this->Addresses->contains($address)) {
            $this->Addresses->removeElement($address);
            // set the owning side to null (unless already changed)
            if ($address->getClient() === $this) {
                $address->setClient(null);
            }
        }

        return $this;
    }

    public function getAgent(): ?Agent
    {
        return $this->agent;
    }

    public function setAgent(?Agent $agent): self
    {
        $this->agent = $agent;

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
            $order->setClient($this);
        }

        return $this;
    }

    public function removeOrder(QuoteOrder $order): self
    {
        if ($this->orders->contains($order)) {
            $this->orders->removeElement($order);
            // set the owning side to null (unless already changed)
            if ($order->getClient() === $this) {
                $order->setClient(null);
            }
        }

        return $this;
    }

    public function getBill(): ?Bill
    {
        return $this->bill;
    }

    public function setBill(?Bill $bill): self
    {
        $this->bill = $bill;

        return $this;
    }

    /**
     * @return Collection|Contact[]
     */
    public function getContacts(): Collection
    {
        return $this->contacts;
    }

    public function addContact(Contact $contact): self
    {
        if (!$this->contacts->contains($contact)) {
            $this->contacts[] = $contact;
            $contact->setClient($this);
        }

        return $this;
    }

    public function removeContact(Contact $contact): self
    {
        if ($this->contacts->contains($contact)) {
            $this->contacts->removeElement($contact);
            // set the owning side to null (unless already changed)
            if ($contact->getClient() === $this) {
                $contact->setClient(null);
            }
        }

        return $this;
    }
}
