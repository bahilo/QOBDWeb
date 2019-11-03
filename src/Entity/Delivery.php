<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DeliveryRepository")
 */
class Delivery
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
    private $Package;

    /**
     * @ORM\Column(type="datetime")
     */
    private $CreatedAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\DeliveryStatus", inversedBy="deliveries")
     */
    private $Status;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Address", inversedBy="deliveries")
     */
    private $Address;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\QuoteOrder", mappedBy="Delivery")
     */
    private $orders;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Item", inversedBy="deliveries")
     */
    private $Items;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
        $this->Items = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPackage(): ?int
    {
        return $this->Package;
    }

    public function setPackage(int $Package): self
    {
        $this->Package = $Package;

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

    public function getStatus(): ?DeliveryStatus
    {
        return $this->Status;
    }

    public function setStatus(?DeliveryStatus $Status): self
    {
        $this->Status = $Status;

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
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(QuoteOrder $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setDelivery($this);
        }

        return $this;
    }

    public function removeOrder(QuoteOrder $order): self
    {
        if ($this->orders->contains($order)) {
            $this->orders->removeElement($order);
            // set the owning side to null (unless already changed)
            if ($order->getDelivery() === $this) {
                $order->setDelivery(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Item[]
     */
    public function getItems(): Collection
    {
        return $this->Items;
    }

    public function addItem(Item $item): self
    {
        if (!$this->Items->contains($item)) {
            $this->Items[] = $item;
        }

        return $this;
    }

    public function removeItem(Item $item): self
    {
        if ($this->Items->contains($item)) {
            $this->Items->removeElement($item);
        }

        return $this;
    }
}
