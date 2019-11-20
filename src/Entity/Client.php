<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\Collection;
use JMS\Serializer\Annotation\SerializedName;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ClientRepository")
 */
class Client
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
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"class_property"})
     * @SerializedName("CompanyName")
     */
    private $CompanyName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"class_property"})
     * @SerializedName("Rib")
     */
    private $Rib;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"class_property"})
     * @SerializedName("CRN")
     */
    private $CRN;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"class_property"})
     * @SerializedName("PayDelay")
     */
    private $PayDelay;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"class_property"})
     * @SerializedName("MaxCredit")
     */
    private $MaxCredit;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"class_property"})
     * @SerializedName("IsActivated")
     */
    private $IsActivated;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Agent", inversedBy="Clients")
     * @Groups({"relation_property"})
     */
    private $agent;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\QuoteOrder", mappedBy="Client")
     * @Groups({"relation_property"})
     */
    private $orders;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Bill", inversedBy="Client")
     * @Groups({"relation_property"})
     */
    private $bill;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Contact", mappedBy="Client")
     * @Groups({"relation_property"})
     */
    private $contacts;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"class_property"})
     * @SerializedName("IsProspect")
     */
    private $IsProspect;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"class_property"})
     * @SerializedName("Denomination")
     */
    private $Denomination;


    // contact principal
    /**
     * @Groups({"class_property"})
     * @SerializedName("FirstName")
     */
    private $FirstName;
    /**
     * @Groups({"class_property"})
     * @SerializedName("LastName")
     */
    private $LastName;
    /**
     * @Groups({"class_property"})
     * @SerializedName("Email")
     */
    private $Email;
    /**
     * @Groups({"class_property"})
     * @SerializedName("Phone")
     */
    private $Phone;
    /**
     * @Groups({"class_property"})
     * @SerializedName("Fax")
     */
    private $Fax;

    /**
     * @Groups({"class_property"})
     * @SerializedName("City")
     */
    private $City;
    /**
     * @Groups({"class_property"})
     * @SerializedName("Street")
     */
    private $Street;
    /**
     * @Groups({"class_property"})
     * @SerializedName("ZipCode")
     */
    private $ZipCode;
    /**
     * @Groups({"class_property"})
     * @SerializedName("Country")
     */
    private $Country;
    /**
     * @Groups({"class_property"})
     * @SerializedName("AddressComment")
     */
    private $AddressComment;
    /**
     * @Groups({"class_property"})
     * @SerializedName("ClientComment")
     */
    private $ClientComment;
    /**
     * @Groups({"class_property"})
     * @SerializedName("AddressName")
     */
    private $AddressName;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Comment", cascade={"persist", "remove"})
     */
    private $Comment;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Bill", mappedBy="Client")
     */
    private $bills;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
        $this->contacts = new ArrayCollection();
        $this->bills = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getIsProspect(): ?bool
    {
        return $this->IsProspect;
    }

    public function setIsProspect(bool $IsProspect): self
    {
        $this->IsProspect = $IsProspect;

        return $this;
    }

    public function getDenomination(): ?string
    {
        return $this->Denomination;
    }

    public function setDenomination(?string $Denomination): self
    {
        $this->Denomination = $Denomination;

        return $this;
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

    public function getClientComment(): ?string
    {
        return $this->ClientComment;
    }

    public function setClientComment(?string $Comment): self
    {
        $this->ClientComment= $Comment;

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
            $bill->setClient($this);
        }

        return $this;
    }

    public function removeBill(Bill $bill): self
    {
        if ($this->bills->contains($bill)) {
            $this->bills->removeElement($bill);
            // set the owning side to null (unless already changed)
            if ($bill->getClient() === $this) {
                $bill->setClient(null);
            }
        }

        return $this;
    }

}
