<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\Collection;
use JMS\Serializer\Annotation\SerializedName;
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
     * @ORM\Column(type="string", length=255)
     * @Groups({"class_property"})
     * @SerializedName("FirstName")
     */
    private $Firstname;

    /**
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
     * @Groups({"class_property"})
     * @SerializedName("ContentComment")
     * */
    private $ContentComment;
    /**
     * @Groups({"class_property"})
     * @SerializedName("City")     * 
     * */
    private $City;
    /**
     * @Groups({"class_property"})
     * @SerializedName("Street")     * 
     * */
    private $Street;
    /**
     * @Groups({"class_property"})
     * @SerializedName("ZipCode")     * 
     * */
    private $ZipCode;
    /**
     * @Groups({"class_property"})
     * @SerializedName("Country")     * 
     * */
    private $Country;
    /**
     * @Groups({"class_property"})
     * @SerializedName("AddressComment")     * 
     * */
    private $AddressComment;
    /**
     * @Groups({"class_property"})
     * @SerializedName("AddressName")     * 
     * */
    private $AddressName;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Address", cascade={"persist", "remove"})
     * @Groups({"relation_property"})
     */
    private $Address;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\QuoteOrder", mappedBy="Contact")
     */
    private $quoteOrders;


    public function __construct()
    {
        $this->quoteOrders = new ArrayCollection();
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

    public function getContentComment(): ?string
    {
        return $this->ContentComment;
    }

    public function setContentComment(?string $ContentComment): self
    {
        $this->ContentComment = $ContentComment;

        return $this;
    }

    public function getAddressName(): ?string
    {
        return $this->AddressName;
    }

    public function setAddressName(string $AddressName): self
    {
        $this->AddressName = $AddressName;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->City;
    }

    public function setCity(string $City): self
    {
        $this->City = $City;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->Street;
    }

    public function setStreet(string $Street): self
    {
        $this->Street = $Street;

        return $this;
    }

    public function getZipCode(): ?string
    {
        return $this->ZipCode;
    }

    public function setZipCode(string $ZipCode): self
    {
        $this->ZipCode = $ZipCode;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->Country;
    }

    public function setCountry(string $Country): self
    {
        $this->Country = $Country;

        return $this;
    }

    public function getAddressComment(): ?string
    {
        return $this->AddressComment;
    }

    public function setAddressComment(?string $Comment): self
    {
        $this->AddressComment= $Comment;

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
}
