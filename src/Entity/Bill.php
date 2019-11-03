<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BillRepository")
 */
class Bill
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $PayMode;

    /**
     * @ORM\Column(type="float")
     */
    private $Pay;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $PayReceived;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Comment", cascade={"persist", "remove"})
     */
    private $PrivateComment;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Comment", cascade={"persist", "remove"})
     */
    private $PublicComment;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Client", mappedBy="bill")
     */
    private $Client;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\QuoteOrder", inversedBy="bills")
     */
    private $Order_;

    /**
     * @ORM\Column(type="datetime")
     */
    private $CreatedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $LimitDateAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $PayedAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Address", inversedBy="bills")
     */
    private $Address;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Alert", mappedBy="Bill")
     */
    private $alerts;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\IncomeStatistic", inversedBy="Bill")
     */
    private $incomeStatistic;

    public function __construct()
    {
        $this->Client = new ArrayCollection();
        $this->alerts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPayMode(): ?string
    {
        return $this->PayMode;
    }

    public function setPayMode(?string $PayMode): self
    {
        $this->PayMode = $PayMode;

        return $this;
    }

    public function getPay(): ?float
    {
        return $this->Pay;
    }

    public function setPay(float $Pay): self
    {
        $this->Pay = $Pay;

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

    /**
     * @return Collection|Client[]
     */
    public function getClient(): Collection
    {
        return $this->Client;
    }

    public function addClient(Client $client): self
    {
        if (!$this->Client->contains($client)) {
            $this->Client[] = $client;
            $client->setBill($this);
        }

        return $this;
    }

    public function removeClient(Client $client): self
    {
        if ($this->Client->contains($client)) {
            $this->Client->removeElement($client);
            // set the owning side to null (unless already changed)
            if ($client->getBill() === $this) {
                $client->setBill(null);
            }
        }

        return $this;
    }

    public function getOrder(): ?QuoteOrder
    {
        return $this->Order_;
    }

    public function setOrder(?QuoteOrder $Order_): self
    {
        $this->Order_ = $Order_;

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

    public function getLimitDateAt(): ?\DateTimeInterface
    {
        return $this->LimitDateAt;
    }

    public function setLimitDateAt(?\DateTimeInterface $LimitDateAt): self
    {
        $this->LimitDateAt = $LimitDateAt;

        return $this;
    }

    public function getPayedAt(): ?\DateTimeInterface
    {
        return $this->PayedAt;
    }

    public function setPayedAt(?\DateTimeInterface $PayedAt): self
    {
        $this->PayedAt = $PayedAt;

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
     * @return Collection|Alert[]
     */
    public function getAlerts(): Collection
    {
        return $this->alerts;
    }

    public function addAlert(Alert $alert): self
    {
        if (!$this->alerts->contains($alert)) {
            $this->alerts[] = $alert;
            $alert->addBill($this);
        }

        return $this;
    }

    public function removeAlert(Alert $alert): self
    {
        if ($this->alerts->contains($alert)) {
            $this->alerts->removeElement($alert);
            $alert->removeBill($this);
        }

        return $this;
    }

    public function getIncomeStatistic(): ?IncomeStatistic
    {
        return $this->incomeStatistic;
    }

    public function setIncomeStatistic(?IncomeStatistic $incomeStatistic): self
    {
        $this->incomeStatistic = $incomeStatistic;

        return $this;
    }
}
