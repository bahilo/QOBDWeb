<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\IncomeStatisticRepository")
 */
class IncomeStatistic
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Bill", mappedBy="incomeStatistic")
     */
    private $Bill;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Company;

    /**
     * @ORM\Column(type="float")
     */
    private $PurchaseTotal;

    /**
     * @ORM\Column(type="float")
     */
    private $SellTotal;

    /**
     * @ORM\Column(type="float")
     */
    private $PercentIncome;

    /**
     * @ORM\Column(type="float")
     */
    private $Income;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $PayReceived;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Tax", mappedBy="incomeStatistic")
     */
    private $Tax;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $LimitDateAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $PayDateAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $CreatedAt;

    public function __construct()
    {
        $this->Bill = new ArrayCollection();
        $this->Tax = new ArrayCollection();
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
            $bill->setIncomeStatistic($this);
        }

        return $this;
    }

    public function removeBill(Bill $bill): self
    {
        if ($this->Bill->contains($bill)) {
            $this->Bill->removeElement($bill);
            // set the owning side to null (unless already changed)
            if ($bill->getIncomeStatistic() === $this) {
                $bill->setIncomeStatistic(null);
            }
        }

        return $this;
    }

    public function getCompany(): ?string
    {
        return $this->Company;
    }

    public function setCompany(?string $Company): self
    {
        $this->Company = $Company;

        return $this;
    }

    public function getPurchaseTotal(): ?float
    {
        return $this->PurchaseTotal;
    }

    public function setPurchaseTotal(float $PurchaseTotal): self
    {
        $this->PurchaseTotal = $PurchaseTotal;

        return $this;
    }

    public function getSellTotal(): ?float
    {
        return $this->SellTotal;
    }

    public function setSellTotal(float $SellTotal): self
    {
        $this->SellTotal = $SellTotal;

        return $this;
    }

    public function getPercentIncome(): ?float
    {
        return $this->PercentIncome;
    }

    public function setPercentIncome(float $PercentIncome): self
    {
        $this->PercentIncome = $PercentIncome;

        return $this;
    }

    public function getIncome(): ?float
    {
        return $this->Income;
    }

    public function setIncome(float $Income): self
    {
        $this->Income = $Income;

        return $this;
    }

    public function getPayReceived(): ?float
    {
        return $this->PayReceived;
    }

    public function setPayReceived(?float $PayReceived): self
    {
        $this->PayReceived = $PayReceived;

        return $this;
    }

    /**
     * @return Collection|Tax[]
     */
    public function getTax(): Collection
    {
        return $this->Tax;
    }

    public function addTax(Tax $tax): self
    {
        if (!$this->Tax->contains($tax)) {
            $this->Tax[] = $tax;
            $tax->setIncomeStatistic($this);
        }

        return $this;
    }

    public function removeTax(Tax $tax): self
    {
        if ($this->Tax->contains($tax)) {
            $this->Tax->removeElement($tax);
            // set the owning side to null (unless already changed)
            if ($tax->getIncomeStatistic() === $this) {
                $tax->setIncomeStatistic(null);
            }
        }

        return $this;
    }

    public function getLimitDateAt(): ?\DateTimeInterface
    {
        return $this->LimitDateAt;
    }

    public function setLimitDateAt(?\DateTimeInterface $LimitDateAt): self
    {
        $this->LimitDateAt = $LimitDateAt;

        return $this;
    }

    public function getPayDateAt(): ?\DateTimeInterface
    {
        return $this->PayDateAt;
    }

    public function setPayDateAt(?\DateTimeInterface $PayDateAt): self
    {
        $this->PayDateAt = $PayDateAt;

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
