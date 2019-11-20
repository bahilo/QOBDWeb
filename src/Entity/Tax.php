<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\Collection;
use JMS\Serializer\Annotation\SerializedName;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TaxRepository")
 */
class Tax
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
     * @ORM\Column(type="string", length=255)
     * @Groups({"class_property"})
     * @SerializedName("Type")
     */
    private $Type;

    /**
     * @ORM\Column(type="float")
     * @Groups({"class_property"})
     * @SerializedName("Value")
     */
    private $Value;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"class_property"})
     * @SerializedName("IsCurrent")
     */
    private $IsCurrent;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Comment", cascade={"persist", "remove"})
     */
    private $Comment;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"class_property"})
     * @SerializedName("CreateAt")
     */
    private $CreateAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\IncomeStatistic", inversedBy="Tax")
     */
    private $incomeStatistic;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Item", mappedBy="Tax")
     */
    private $items;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\QuoteOrderDetail", mappedBy="Tax")
     */
    private $quoteOrderDetails;

    /**
     * @Groups({"class_property"})
     * @SerializedName("CommentContent")
     * 
     */
    private $CommentContent;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"class_property"})
     * @SerializedName("IsTVAMarge")
     */
    private $IsTVAMarge;


    public function __construct()
    {
        $this->items = new ArrayCollection();
        $this->quoteOrderDetails = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->Type;
    }

    public function setType(string $Type): self
    {
        $this->Type = $Type;

        return $this;
    }

    public function getValue(): ?float
    {
        return $this->Value;
    }

    public function setValue(float $Value): self
    {
        $this->Value = $Value;

        return $this;
    }

    public function getIsCurrent(): ?bool
    {
        return $this->IsCurrent;
    }

    public function setIsCurrent(bool $IsCurrent): self
    {
        $this->IsCurrent = $IsCurrent;

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

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->CreateAt;
    }

    public function setCreateAt(\DateTimeInterface $CreateAt): self
    {
        $this->CreateAt = $CreateAt;

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

    /**
     * @return Collection|Item[]
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(Item $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items[] = $item;
            $item->setTax($this);
        }

        return $this;
    }

    public function removeItem(Item $item): self
    {
        if ($this->items->contains($item)) {
            $this->items->removeElement($item);
            // set the owning side to null (unless already changed)
            if ($item->getTax() === $this) {
                $item->setTax(null);
            }
        }

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
            $quoteOrderDetail->setTax($this);
        }

        return $this;
    }

    public function removeQuoteOrderDetail(QuoteOrderDetail $quoteOrderDetail): self
    {
        if ($this->quoteOrderDetails->contains($quoteOrderDetail)) {
            $this->quoteOrderDetails->removeElement($quoteOrderDetail);
            // set the owning side to null (unless already changed)
            if ($quoteOrderDetail->getTax() === $this) {
                $quoteOrderDetail->setTax(null);
            }
        }

        return $this;
    }
   

    public function getCommentContent(): ?string
    {
        return $this->CommentContent;
    }

    public function setCommentContent(?string $CommentContent): self
    {
        $this->CommentContent = $CommentContent;

        return $this;
    }

    public function getIsTVAMarge(): ?bool
    {
        return $this->IsTVAMarge;
    }

    public function setIsTVAMarge(bool $IsTVAMarge): self
    {
        $this->IsTVAMarge = $IsTVAMarge;

        return $this;
    }
}
