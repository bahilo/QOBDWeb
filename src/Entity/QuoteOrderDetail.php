<?php

namespace App\Entity;

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
     * @Groups({"class_relation"})
     */
    private $Item;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"class_property"})
     * @SerializedName("Quantity") 
     */
    private $Quantity;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Delivery", inversedBy="quoteOrderDetails")
     * @Groups({"class_relation"})
     */
    private $Delivery;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Bill", inversedBy="quoteOrderDetails")
     * @Groups({"class_relation"})
     */
    private $Bill;
    
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tax", inversedBy="quoteOrderDetails")
     * @Groups({"class_relation"})
     */
    private $Tax;


    /**
     * @Groups({"class_property"})
     * @SerializedName("ItemRef") 
     */
    private $ItemRef;

    /**
     * @Groups({"class_property"})
     * @SerializedName("ItemName") 
     */
    private $ItemName;

    /**
     * @Groups({"class_property"})
     * @SerializedName("ItemPurchasePrice") 
     */
    private $ItemPurchasePrice;
 
    /**
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

    public function getDelivery(): ?Delivery
    {
        return $this->Delivery;
    }

    public function setDelivery(?Delivery $Delivery): self
    {
        $this->Delivery = $Delivery;

        return $this;
    }

    public function getBill(): ?Bill
    {
        return $this->Bill;
    }

    public function setBill(?Bill $Bill): self
    {
        $this->Bill = $Bill;

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

    public function getItemRef(): ?string
    {
        return $this->ItemRef;
    }

    public function setItemRef(?string $ItemRef): self
    {
        $this->ItemRef = $ItemRef;

        return $this;
    }

    public function getItemName(): ?string
    {
        return $this->ItemName;
    }

    public function setItemName(?string $ItemName): self
    {
        $this->ItemName = $ItemName;

        return $this;
    }

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

    public function setItemSellPrice(?string $ItemSellPrice): self
    {
        $this->ItemSellPrice = $ItemSellPrice;

        return $this;
    }

    public function getItemSellPriceTotal(): ?string
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
}
