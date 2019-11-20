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
     * @ORM\OneToMany(targetEntity="App\Entity\QuantityDelivery", mappedBy="Delivery")
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
            $quantityDelivery->setDelivery($this);
        }

        return $this;
    }

    public function removeQuantityDelivery(QuantityDelivery $quantityDelivery): self
    {
        if ($this->quantityDeliveries->contains($quantityDelivery)) {
            $this->quantityDeliveries->removeElement($quantityDelivery);
            // set the owning side to null (unless already changed)
            if ($quantityDelivery->getDelivery() === $this) {
                $quantityDelivery->setDelivery(null);
            }
        }

        return $this;
    }
}
