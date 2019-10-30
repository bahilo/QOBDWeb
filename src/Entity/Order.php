<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrderRepository")
 */
class Order
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Comment", cascade={"persist", "remove"})
     */
    private $AdminComment;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Comment", cascade={"persist", "remove"})
     */
    private $PrivateComment;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Comment", inversedBy="Order_", cascade={"persist", "remove"})
     */
    private $PublicComment;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Agent", inversedBy="orders")
     */
    private $Agent;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Client", inversedBy="orders")
     */
    private $Client;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Currency", inversedBy="orders")
     */
    private $Currency;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tax", inversedBy="orders")
     */
    private $Tax;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\OrderStatus", inversedBy="orders")
     */
    private $Status;

    /**
     * @ORM\Column(type="datetime")
     */
    private $CreatedAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Bill", mappedBy="Order_")
     */
    private $bills;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Delivery", inversedBy="orders")
     */
    private $Delivery;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Item", inversedBy="orders")
     */
    private $Items;

    public function __construct()
    {
        $this->bills = new ArrayCollection();
        $this->Items = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAdminComment(): ?Comment
    {
        return $this->AdminComment;
    }

    public function setAdminComment(?Comment $AdminComment): self
    {
        $this->AdminComment = $AdminComment;

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

    public function getAgent(): ?Agent
    {
        return $this->Agent;
    }

    public function setAgent(?Agent $Agent): self
    {
        $this->Agent = $Agent;

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

    public function getCurrency(): ?Currency
    {
        return $this->Currency;
    }

    public function setCurrency(?Currency $Currency): self
    {
        $this->Currency = $Currency;

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

    public function getStatus(): ?OrderStatus
    {
        return $this->Status;
    }

    public function setStatus(?OrderStatus $Status): self
    {
        $this->Status = $Status;

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
            $bill->setOrder($this);
        }

        return $this;
    }

    public function removeBill(Bill $bill): self
    {
        if ($this->bills->contains($bill)) {
            $this->bills->removeElement($bill);
            // set the owning side to null (unless already changed)
            if ($bill->getOrder() === $this) {
                $bill->setOrder(null);
            }
        }

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
