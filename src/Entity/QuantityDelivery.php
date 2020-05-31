<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\SerializedName;

/**
 * @ORM\Entity(repositoryClass="App\Repository\QuantityDeliveryRepository")
 */
class QuantityDelivery
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
     * @ORM\Column(type="integer")
     * @Groups({"class_property"})
     * @SerializedName("Quantity") 
     */
    private $Quantity;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\QuoteOrderDetail", inversedBy="quantityDeliveries")
     * @Groups({"class_property"})
     * @SerializedName("OrderDetail")
     */
    private $OrderDetail;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Delivery", inversedBy="quantityDeliveries")
     */
    private $Delivery;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Bill", inversedBy="quantityDeliveries")
     */
    private $Bill;

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
     * @SerializedName("ItemSellPriceVATTotal") 
     */
    private $ItemSellPriceVATTotal;

    /**
     * @Groups({"class_property"})
     * @SerializedName("ItemROIPercent") 
     */
    private $ItemROIPercent;


    public function getId(): ?int
    {
        return $this->id;
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

    public function getOrderDetail(): ?QuoteOrderDetail
    {
        return $this->OrderDetail;
    }

    public function setOrderDetail(?QuoteOrderDetail $OrderDetail): self
    {
        $this->OrderDetail = $OrderDetail;

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
}
