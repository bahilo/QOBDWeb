<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ItemRepository")
 */
class Item
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Ref;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Name;

    /**
     * @ORM\Column(type="float")
     */
    private $SellPrice;

    /**
     * @ORM\Column(type="float")
     */
    private $PurchasePrice;

    /**
     * @ORM\Column(type="integer")
     */
    private $Stock;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Picture;

    /**
     * @ORM\Column(type="boolean")
     */
    private $IsErasable;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Comment", cascade={"persist", "remove"})
     */
    private $Comment;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Provider", inversedBy="items")
     */
    private $Provider;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ItemGroupe", inversedBy="items")
     */
    private $ItemGroupe;

    /**
     * @ORM\Column(type="datetime")
     */
    private $CreatedAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ItemBrand", inversedBy="items")
     */
    private $ItemBrand;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Delivery", mappedBy="Items")
     */
    private $deliveries;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Order", mappedBy="Items")
     */
    private $orders;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tax", inversedBy="items")
     */
    private $Tax;

    public function __construct()
    {
        $this->Provider = new ArrayCollection();
        $this->deliveries = new ArrayCollection();
        $this->orders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRef(): ?string
    {
        return $this->Ref;
    }

    public function setRef(string $Ref): self
    {
        $this->Ref = $Ref;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    public function getSellPrice(): ?float
    {
        return $this->SellPrice;
    }

    public function setSellPrice(float $SellPrice): self
    {
        $this->SellPrice = $SellPrice;

        return $this;
    }

    public function getPurchasePrice(): ?float
    {
        return $this->PurchasePrice;
    }

    public function setPurchasePrice(float $PurchasePrice): self
    {
        $this->PurchasePrice = $PurchasePrice;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->Stock;
    }

    public function setStock(int $Stock): self
    {
        $this->Stock = $Stock;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->Picture;
    }

    public function setPicture(?string $Picture): self
    {
        $this->Picture = $Picture;

        return $this;
    }

    public function getIsErasable(): ?bool
    {
        return $this->IsErasable;
    }

    public function setIsErasable(bool $IsErasable): self
    {
        $this->IsErasable = $IsErasable;

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
     * @return Collection|Provider[]
     */
    public function getProvider(): Collection
    {
        return $this->Provider;
    }

    public function addProvider(Provider $provider): self
    {
        if (!$this->Provider->contains($provider)) {
            $this->Provider[] = $provider;
        }

        return $this;
    }

    public function removeProvider(Provider $provider): self
    {
        if ($this->Provider->contains($provider)) {
            $this->Provider->removeElement($provider);
        }

        return $this;
    }

    public function getItemGroupe(): ?ItemGroupe
    {
        return $this->ItemGroupe;
    }

    public function setItemGroupe(?ItemGroupe $ItemGroupe): self
    {
        $this->ItemGroupe = $ItemGroupe;

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

    public function getItemBrand(): ?ItemBrand
    {
        return $this->ItemBrand;
    }

    public function setItemBrand(?ItemBrand $ItemBrand): self
    {
        $this->ItemBrand = $ItemBrand;

        return $this;
    }

    /**
     * @return Collection|Delivery[]
     */
    public function getDeliveries(): Collection
    {
        return $this->deliveries;
    }

    public function addDelivery(Delivery $delivery): self
    {
        if (!$this->deliveries->contains($delivery)) {
            $this->deliveries[] = $delivery;
            $delivery->addItem($this);
        }

        return $this;
    }

    public function removeDelivery(Delivery $delivery): self
    {
        if ($this->deliveries->contains($delivery)) {
            $this->deliveries->removeElement($delivery);
            $delivery->removeItem($this);
        }

        return $this;
    }

    /**
     * @return Collection|Order[]
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->addItem($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->contains($order)) {
            $this->orders->removeElement($order);
            $order->removeItem($this);
        }

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
}
