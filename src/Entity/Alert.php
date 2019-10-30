<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AlertRepository")
 */
class Alert
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Bill", inversedBy="alerts")
     */
    private $Bill;

    /**
     * @ORM\Column(type="datetime")
     */
    private $ReminderAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $CreatedAt;

    public function __construct()
    {
        $this->Bill = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Bill[]
     */
    public function getBill(): Collection
    {
        return $this->Bill;
    }

    public function addBill(Bill $bill): self
    {
        if (!$this->Bill->contains($bill)) {
            $this->Bill[] = $bill;
        }

        return $this;
    }

    public function removeBill(Bill $bill): self
    {
        if ($this->Bill->contains($bill)) {
            $this->Bill->removeElement($bill);
        }

        return $this;
    }

    public function getReminderAt(): ?\DateTimeInterface
    {
        return $this->ReminderAt;
    }

    public function setReminderAt(\DateTimeInterface $ReminderAt): self
    {
        $this->ReminderAt = $ReminderAt;

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
}
