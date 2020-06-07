<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\SerializedName;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ContactRepository")
 */
class Contact
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"class_property"})
     * @SerializedName("id")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Client", inversedBy="contacts")
     * @Groups({"relation_property"})
     */
    private $Client;

    /**
     * @Assert\NotBlank(message = "Le prénom du contact ne peux pas être vide")
     * @ORM\Column(type="string", length=255)
     * @Groups({"class_property"})
     * @SerializedName("FirstName")
     */
    private $Firstname;

    /**
     * @Assert\NotBlank(message = "Le nom du contact ne peux pas être vide")
     * @ORM\Column(type="string", length=255)
     * @Groups({"class_property"})
     * @SerializedName("LastName")
     */
    private $LastName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"class_property"})
     * @SerializedName("Position")
     */
    private $Position;

    /**
     * @Assert\NotBlank(message = "L'email du contact ne peux pas être vide")
     * @ORM\Column(type="string", length=255)
     * @Groups({"class_property"})
     * @SerializedName("Email")
     */
    private $Email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"class_property"})
     * @SerializedName("Phone")
     */
    private $Phone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"class_property"})
     * @SerializedName("Mobile")
     */
    private $Mobile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"class_property"})
     * @SerializedName("Fax")
     */
    private $Fax;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Comment", cascade={"persist", "remove"})
     * @Groups({"relation_property"})
     */
    private $Comment;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Provider", inversedBy="Contact")
     * @Groups({"relation_property"})
     */
    private $provider;


    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"class_property"})
     * @SerializedName("IsPrincipal")
     */
    private $IsPrincipal;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Address", cascade={"persist", "remove"})
     * @Groups({"relation_property"})
     */
    private $Address;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\QuoteOrder", mappedBy="Contact")
     */
    private $quoteOrders;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Bill", mappedBy="Contact")
     */
    private $bills;


    public function __construct()
    {
        $this->quoteOrders = new ArrayCollection();
        $this->bills = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClient(): ?Client
    {
        return $this->Client;
    }

    public function setClient(?Client $Client): self
    {
        $this->Client = $Client;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->Firstname;
    }

    public function setFirstname(string $Firstname): self
    {
        $this->Firstname = $Firstname;

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

    public function getPosition(): ?string
    {
        return $this->Position;
    }

    public function setPosition(?string $Position): self
    {
        $this->Position = $Position;

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

    public function getMobile(): ?string
    {
        return $this->Mobile;
    }

    public function setMobile(?string $Mobile): self
    {
        $this->Mobile = $Mobile;

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

    public function getComment(): ?Comment
    {
        return $this->Comment;
    }

    public function setComment(?Comment $Comment): self
    {
        $this->Comment = $Comment;

        return $this;
    }

    public function getProvider(): ?Provider
    {
        return $this->provider;
    }

    public function setProvider(?Provider $provider): self
    {
        $this->provider = $provider;

        return $this;
    }

    public function getIsPrincipal(): ?bool
    {
        return $this->IsPrincipal;
    }

    public function setIsPrincipal(?bool $IsPrincipal): self
    {
        $this->IsPrincipal = $IsPrincipal;

        return $this;
    }

    public function getAddress(): ?Address
    {
        return $this->Address;
    }

    public function setAddress(?Address $Address): self
    {
        $this->Address = $Address;

        return $this;
    }

    /**
     * @return Collection|QuoteOrder[]
     */
    public function getQuoteOrders(): Collection
    {
        return $this->quoteOrders;
    }

    public function addQuoteOrder(QuoteOrder $quoteOrder): self
    {
        if (!$this->quoteOrders->contains($quoteOrder)) {
            $this->quoteOrders[] = $quoteOrder;
            $quoteOrder->setContact($this);
        }

        return $this;
    }

    public function removeQuoteOrder(QuoteOrder $quoteOrder): self
    {
        if ($this->quoteOrders->contains($quoteOrder)) {
            $this->quoteOrders->removeElement($quoteOrder);
            // set the owning side to null (unless already changed)
            if ($quoteOrder->getContact() === $this) {
                $quoteOrder->setContact(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Bill[]
     */
    public function getBills(): Collection
    {
        return $this->bills;
    }

    public function addBill(Bill $bill): self
    {
        if (!$this->bills->contains($bill)) {
            $this->bills[] = $bill;
            $bill->setContact($this);
        }

        return $this;
    }

    public function removeBill(Bill $bill): self
    {
        if ($this->bills->contains($bill)) {
            $this->bills->removeElement($bill);
            // set the owning side to null (unless already changed)
            if ($bill->getContact() === $this) {
                $bill->setContact(null);
            }
        }

        return $this;
    }
}
