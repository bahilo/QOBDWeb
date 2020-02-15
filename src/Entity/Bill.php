<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\Collection;
use JMS\Serializer\Annotation\SerializedName;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BillRepository")
 */
class Bill
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
     * @SerializedName("PayMode")
     */
    private $PayMode;

    /**
     * @ORM\Column(type="float")
     * @Groups({"class_property"})
     * @SerializedName("Pay")
     */
    private $Pay;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"class_property"})
     * @SerializedName("PayReceived")
     */
    private $PayReceived;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Comment", cascade={"persist", "remove"})
     * @Groups({"class_relation"})
     */
    private $PrivateComment;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Comment", cascade={"persist", "remove"})
     * @Groups({"class_relation"})
     */
    private $PublicComment;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"class_property"})
     * @SerializedName("CreatedAt")
     */
    private $CreatedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"class_property"})
     * @SerializedName("LimitDateAt")
     */
    private $LimitDateAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"class_property"})
     * @SerializedName("PayedAt")
     */
    private $PayedAt;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Alert", mappedBy="Bill")
     * @Groups({"class_relation"})
     */
    private $alerts;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\IncomeStatistic", inversedBy="Bill")
     * @Groups({"class_relation"})
     */
    private $incomeStatistic;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Contact", inversedBy="bills")
     * @Groups({"class_relation"})
     */
    private $Contact;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Client", inversedBy="bills")
     * @Groups({"class_relation"})
     */
    private $Client;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\QuantityDelivery", mappedBy="Bill")
     * @Groups({"class_relation"})
     */
    private $quantityDeliveries;

    /**
     * @Groups({"class_property"})
     * @SerializedName("ItemSellPriceVATTotal") 
     */
    private $ItemSellPriceVATTotal;

    /**
     * @Groups({"class_property"})
     * @SerializedName("BillPublicComment") 
     */
    private $BillPublicComment;

    /**
     * @Groups({"class_property"})
     * @SerializedName("BillPrivateComment") 
     */
    private $BillPrivateComment;
    

    public function __construct()
    {
        $this->Client = new ArrayCollection();
        $this->alerts = new ArrayCollection();
        $this->quantityDeliveries = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPayMode(): ?string
    {
        return $this->PayMode;
    }

    public function setPayMode(?string $PayMode): self
    {
        $this->PayMode = $PayMode;

        return $this;
    }

    public function getPay(): ?float
    {
        return $this->Pay;
    }

    public function setPay(float $Pay): self
    {
        $this->Pay = $Pay;

        return $this;
    }

    public function getPayReceived(): ?float
    {
        return $this->PayReceived;
    }

    public function setPayReceived(?float $PayReceived): self
    {
        $this->PayReceived = $PayReceived;

        return $this;
    }

    public function getPrivateComment(): ?Comment
    {
        return $this->PrivateComment;
    }

    public function setPrivateComment(?Comment $PrivateComment): self
    {
        $this->PrivateComment = $PrivateComment;

        return $this;
    }

    public function getPublicComment(): ?Comment
    {
        return $this->PublicComment;
    }

    public function setPublicComment(?Comment $PublicComment): self
    {
        $this->PublicComment = $PublicComment;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->CreatedAt;
    }

    public function setCreatedAt(\DateTimeInterface $CreatedAt): self
    {
        $this->CreatedAt = $CreatedAt;

        return $this;
    }

    public function getLimitDateAt(): ?\DateTimeInterface
    {
        return $this->LimitDateAt;
    }

    public function setLimitDateAt(?\DateTimeInterface $LimitDateAt): self
    {
        $this->LimitDateAt = $LimitDateAt;

        return $this;
    }

    public function getPayedAt(): ?\DateTimeInterface
    {
        return $this->PayedAt;
    }

    public function setPayedAt(?\DateTimeInterface $PayedAt): self
    {
        $this->PayedAt = $PayedAt;

        return $this;
    }

    /**
     * @return Collection|Alert[]
     */
    public function getAlerts(): Collection
    {
        return $this->alerts;
    }

    public function addAlert(Alert $alert): self
    {
        if (!$this->alerts->contains($alert)) {
            $this->alerts[] = $alert;
            $alert->addBill($this);
        }

        return $this;
    }

    public function removeAlert(Alert $alert): self
    {
        if ($this->alerts->contains($alert)) {
            $this->alerts->removeElement($alert);
            $alert->removeBill($this);
        }

        return $this;
    }

    public function getIncomeStatistic(): ?IncomeStatistic
    {
        return $this->incomeStatistic;
    }

    public function setIncomeStatistic(?IncomeStatistic $incomeStatistic): self
    {
        $this->incomeStatistic = $incomeStatistic;

        return $this;
    }

    public function getContact(): ?Contact
    {
        return $this->Contact;
    }

    public function setContact(?Contact $Contact): self
    {
        $this->Contact = $Contact;

        return $this;
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

    /**
     * @return Collection|QuantityDelivery[]
     */
    public function getQuantityDeliveries(): Collection
    {
        return $this->quantityDeliveries;
    }

    public function addQuantityDelivery(QuantityDelivery $quantityDelivery): self
    {
        if (!$this->quantityDeliveries->contains($quantityDelivery)) {
            $this->quantityDeliveries[] = $quantityDelivery;
            $quantityDelivery->setBill($this);
        }

        return $this;
    }

    public function removeQuantityDelivery(QuantityDelivery $quantityDelivery): self
    {
        if ($this->quantityDeliveries->contains($quantityDelivery)) {
            $this->quantityDeliveries->removeElement($quantityDelivery);
            // set the owning side to null (unless already changed)
            if ($quantityDelivery->getBill() === $this) {
                $quantityDelivery->setBill(null);
            }
        }

        return $this;
    }

    public function getItemSellPriceVATTotal(): ?string
    {
        return $this->ItemSellPriceVATTotal;
    }

    public function setItemSellPriceVATTotal(?string $ItemSellPriceVATTotal): self
    {
        $this->ItemSellPriceVATTotal = $ItemSellPriceVATTotal;

        return $this;
    }

    public function getBillPublicComment(): ?string
    {
        return $this->BillPublicComment;
    }

    public function setBillPublicComment(?string $BillPublicComment): self
    {
        $this->BillPublicComment = $BillPublicComment;

        return $this;
    }

    public function getBillPrivateComment(): ?string
    {
        return $this->BillPrivateComment;
    }

    public function setBillPrivateComment(?string $BillPrivateComment): self
    {
        $this->BillPrivateComment = $BillPrivateComment;

        return $this;
    }
}
