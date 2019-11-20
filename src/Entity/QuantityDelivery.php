<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\QuantityDeliveryRepository")
 */
class QuantityDelivery
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $Quantity;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\QuoteOrderDetail", inversedBy="quantityDeliveries")
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
}
