<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * @ORM\Entity(repositoryClass="App\Repository\QuoteOrderDetailRepository")
 */
class QuoteOrderDetail
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"class_property"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\QuoteOrder", inversedBy="quoteOrderDetails")
     * @Groups({"class_relation"})
     */
    private $QuoteOrder;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Item", inversedBy="quoteOrderDetails")
     * @Groups({"class_relation", "class_property"})
     * @SerializedName("Item") 
     */
    private $Item;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"class_property"})
     * @SerializedName("Quantity") 
     */
    private $Quantity;
    
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tax", inversedBy="quoteOrderDetails")
     * @Groups({"class_relation"})
     */
    private $Tax;

    /**
     * @Groups({"class_property"})
     * @SerializedName("ItemPurchasePrice") 
     */
    private $ItemPurchasePrice;

    /**
     * @ORM\Column(type="float")
     * @Groups({"class_property"})
     * @SerializedName("ItemSellPrice") 
     */
    private $ItemSellPrice;
   
    /**
     * @Groups({"class_property"})
     * @SerializedName("ItemSellPriceTotal") 
     */
    private $ItemSellPriceTotal;
 
    /**
     * @Groups({"class_property"})
     * @SerializedName("ItemSellPriceVATTotal") 
     */
    private $ItemSellPriceVATTotal;
  
    /**
     * @Groups({"class_property"})
     * @SerializedName("ItemROIPercent") 
     */
    private $ItemROIPercent;
    
    /**
     * @Groups({"class_property"})
     * @SerializedName("ItemROICurrency") 
     */
    private $ItemROICurrency;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"class_property"})
     * @SerializedName("QuantityDelivery") 
     */
    private $QuantityDelivery;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"class_property"})
     * @SerializedName("QuantityRecieved") 
     */
    private $QuantityRecieved;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\QuantityDelivery", mappedBy="OrderDetail")
     */
    private $quantityDeliveries;


    public function __construct()
    {
        $this->quantityDeliveries = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuoteOrder(): ?QuoteOrder
    {
        return $this->QuoteOrder;
    }

    public function setQuoteOrder(?QuoteOrder $QuoteOrder): self
    {
        $this->QuoteOrder = $QuoteOrder;

        return $this;
    }

    public function getItem(): ?Item
    {
        return $this->Item;
    }

    public function setItem(?Item $Item): self
    {
        $this->Item = $Item;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->Quantity;
    }

    public function setQuantity(int $Quantity): self
    {
        $this->Quantity = $Quantity;

        return $this;
    }

    public function getTax(): ?Tax
    {
        return $this->Tax;
    }

    public function setTax(?Tax $Tax): self
    {
        $this->Tax = $Tax;

        return $this;
    }

    /*---------------------------*/

    public function getItemPurchasePrice(): ?string
    {
        return $this->ItemPurchasePrice;
    }

    public function setItemPurchasePrice(?string $ItemPurchasePrice): self
    {
        $this->ItemPurchasePrice = $ItemPurchasePrice;

        return $this;
    }

    public function getItemSellPrice(): ?string
    {
        return $this->ItemSellPrice;
    }

    public function setItemSellPrice(?float $ItemSellPrice): self
    {
        $this->ItemSellPrice = $ItemSellPrice;

        return $this;
    }

    public function getItemSellPriceTotal(): ?float
    {
        return $this->ItemSellPriceTotal;
    }

    public function setItemSellPriceTotal(?string $ItemSellPriceTotal): self
    {
        $this->ItemSellPriceTotal = $ItemSellPriceTotal;

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

    public function getItemROIPercent(): ?string
    {
        return $this->ItemROIPercent;
    }

    public function setItemROIPercent(?string $ItemROIPercent): self
    {
        $this->ItemROIPercent = $ItemROIPercent;

        return $this;
    }

    public function getItemROICurrency(): ?string
    {
        return $this->ItemROICurrency;
    }

    public function setItemROICurrency(?string $ItemROICurrency): self
    {
        $this->ItemROICurrency = $ItemROICurrency;

        return $this;
    }

    public function getQuantityDelivery(): ?int
    {
        return $this->QuantityDelivery;
    }

    public function setQuantityDelivery(?int $QuantityDelivery): self
    {
        $this->QuantityDelivery = $QuantityDelivery;

        return $this;
    }

    public function getQuantityRecieved(): ?int
    {
        return $this->QuantityRecieved;
    }

    public function setQuantityRecieved(?int $QuantityRecieved): self
    {
        $this->QuantityRecieved = $QuantityRecieved;

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
            $quantityDelivery->setOrderDetail($this);
        }

        return $this;
    }

    public function removeQuantityDelivery(QuantityDelivery $quantityDelivery): self
    {
        if ($this->quantityDeliveries->contains($quantityDelivery)) {
            $this->quantityDeliveries->removeElement($quantityDelivery);
            // set the owning side to null (unless already changed)
            if ($quantityDelivery->getOrderDetail() === $this) {
                $quantityDelivery->setOrderDetail(null);
            }
        }

        return $this;
    }
    
    /*------------------------------- */
}
