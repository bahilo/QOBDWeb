<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\MaxDepth;
use Doctrine\Common\Collections\Collection;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\ExclusionPolicy;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * @ORM\Entity(repositoryClass="App\Repository\QuoteOrderRepository")
 */
class QuoteOrder
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"class_property"})
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Comment", cascade={"persist", "remove"})
     * @Groups({"class_relation"})
     */
    private $AdminComment;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Comment", cascade={"persist", "remove"})
     * @Groups({"class_relation"})
     */
    private $PrivateComment;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Comment", cascade={"persist", "remove"})
     * @Groups({"class_relation"})
     */
    private $PublicComment;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Agent", inversedBy="orders")
     * @Groups({"class_relation"})
     * @MaxDepth(2)
     */
    private $Agent;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Client", inversedBy="orders")
     * @Groups({"class_relation"})
     * @MaxDepth(2)
     */
    private $Client;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Currency", inversedBy="orders")
     * @Groups({"class_relation"})
     * @MaxDepth(2)
     */
    private $Currency;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\OrderStatus", inversedBy="orders")
     * @Groups({"class_relation"})
     * @MaxDepth(2)
     */
    private $Status;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Contact", inversedBy="quoteOrders")
     */
    private $Contact;
    
    /**
     * @ORM\Column(type="datetime")
     */
    private $CreatedAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\QuoteOrderDetail", mappedBy="QuoteOrder")
     * @Groups({"class_relation"})
     * @MaxDepth(2)
     */
    private $quoteOrderDetails;


    /**
     * @Groups({"class_property"})
     * @SerializedName("AgentFirstName")
     */
    private $AgentFirstName;
    /**
     * @Groups({"class_property"})
     * @SerializedName("AgentLastName")
     */
    private $AgentLastName;
    /**
     * @Groups({"class_property"})
     * @SerializedName("ClientCompanyName")
     */
    private $ClientCompanyName;
    /**
     * @Groups({"class_property"})
     * @SerializedName("CreatedAtToString")
     */
    private $CreatedAtToString;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $IsQuote;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ValidityPeriode;

    /**
     * @ORM\Column(type="boolean")
     */
    private $IsRefVisible;



    public function __construct()
    {
        $this->quoteOrderDetails = new ArrayCollection();
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
     * @return Collection|QuoteOrderDetail[]
     */
    public function getQuoteOrderDetails(): Collection
    {
        return $this->quoteOrderDetails;
    }

    public function addQuoteOrderDetail(QuoteOrderDetail $quoteOrderDetail): self
    {
        if (!$this->quoteOrderDetails->contains($quoteOrderDetail)) {
            $this->quoteOrderDetails[] = $quoteOrderDetail;
            $quoteOrderDetail->setQuoteOrder($this);
        }

        return $this;
    }

    public function removeQuoteOrderDetail(QuoteOrderDetail $quoteOrderDetail): self
    {
        if ($this->quoteOrderDetails->contains($quoteOrderDetail)) {
            $this->quoteOrderDetails->removeElement($quoteOrderDetail);
            // set the owning side to null (unless already changed)
            if ($quoteOrderDetail->getQuoteOrder() === $this) {
                $quoteOrderDetail->setQuoteOrder(null);
            }
        }

        return $this;
    }

    public function getAgentFirstName(): ?string
    {
        return $this->AgentFirstName;
    }

    public function setAgentFirstName(?string $AgentFirstName): self
    {
        $this->AgentFirstName = $AgentFirstName;

        return $this;
    }

    public function getAgentLastName(): ?string
    {
        return $this->AgentLastName;
    }

    public function setAgentLastName(?string $AgentLastName): self
    {
        $this->AgentLastName = $AgentLastName;

        return $this;
    }

    public function getClientCompanyName(): ?string
    {
        return $this->ClientCompanyName;
    }

    public function setClientCompanyName(?string $ClientCompanyName): self
    {
        $this->ClientCompanyName = $ClientCompanyName;

        return $this;
    }

    public function getCreatedAtToString(): ?string
    {
        return $this->CreatedAtToString;
    }

    public function setCreatedAtToString(?string $CreatedAtToString): self
    {
        $this->CreatedAtToString = $CreatedAtToString;

        return $this;
    }

    public function getContact(): ?Contact
    {
        return $this->Contact;
    }

    public function setContact(?Contact $Contact): self
    {
        $this->Contact = $Contact;

        return $this;
    }

    public function getIsQuote(): ?bool
    {
        return $this->IsQuote;
    }

    public function setIsQuote(?bool $IsQuote): self
    {
        $this->IsQuote = $IsQuote;

        return $this;
    }

    public function getValidityPeriode(): ?int
    {
        return $this->ValidityPeriode;
    }

    public function setValidityPeriode(?int $ValidityPeriode): self
    {
        $this->ValidityPeriode = $ValidityPeriode;

        return $this;
    }

    public function getIsRefVisible(): ?bool
    {
        return $this->IsRefVisible;
    }

    public function setIsRefVisible(bool $IsRefVisible): self
    {
        $this->IsRefVisible = $IsRefVisible;

        return $this;
    }
}
