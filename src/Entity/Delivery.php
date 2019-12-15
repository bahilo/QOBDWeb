<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\Collection;
use JMS\Serializer\Annotation\SerializedName;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DeliveryRepository")
 */
class Delivery
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
     * @SerializedName("Package")
     */
    private $Package;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"class_property"})
     * @SerializedName("CreatedAt")
     */
    private $CreatedAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\DeliveryStatus", inversedBy="deliveries")
     * @Groups({"class_relation"})
     */
    private $Status;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\QuantityDelivery", mappedBy="Delivery")
     * @Groups({"class_relation"})
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
