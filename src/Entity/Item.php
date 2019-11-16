<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\Collection;
use JMS\Serializer\Annotation\SerializedName;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ItemRepository")
 */
class Item
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
     * @SerializedName("Ref")
     */
    private $Ref;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"class_property"})
     * @SerializedName("Name")
     */
    private $Name;

    /**
     * @ORM\Column(type="float")
     * @Groups({"class_property"})
     * @SerializedName("SellPrice")
     */
    private $SellPrice;

    /**
     * @ORM\Column(type="float")
     * @Groups({"class_property"})
     * @SerializedName("PurchasePrice")
     */
    private $PurchasePrice;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"class_property"})
     * @SerializedName("Stock")
     */
    private $Stock;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"class_property"})
     * @SerializedName("Picture")
     */
    private $Picture;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"class_property"})
     * @SerializedName("IsErasable")
     */
    private $IsErasable;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Comment", cascade={"persist", "remove"})
     * @Groups({"class_property"})
     * @Groups({"class_relation"})
     */
    private $Comment;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Provider", inversedBy="items")
     * @Groups({"class_relation"})
     */
    private $Provider;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ItemGroupe", inversedBy="items")
     * @Groups({"class_relation"})
     */
    private $ItemGroupe;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"class_property"})
     * @SerializedName("CreatedAt")
     */
    private $CreatedAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ItemBrand", inversedBy="items")
     * @Groups({"class_relation"})
     */
    private $ItemBrand;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tax", inversedBy="items")
     * @Groups({"class_relation"})
     */
    private $Tax;

    /**
     * @Groups({"class_property"})
     * @SerializedName("ContentComment")
     */
    private $ContentComment;
    /**
     * @Groups({"class_property"})
     * @SerializedName("ItemBrandName")
     */
    private $ItemBrandName;
    /**
     * @Groups({"class_property"})
     * @SerializedName("ItemGroupeName")
     */
    private $ItemGroupeName;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\QuoteOrderDetail", mappedBy="Item")
     * @Groups({"class_relation"})
     */
    private $quoteOrderDetails;


    public function __construct()
    {
        $this->Provider = new ArrayCollection();
        $this->quoteOrderDetails = new ArrayCollection();
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

    public function getTax(): ?Tax
    {
        return $this->Tax;
    }

    public function setTax(?Tax $Tax): self
    {
        $this->Tax = $Tax;

        return $this;
    }

    public function getContentComment(): ?string
    {
        return $this->ContentComment;
    }

    public function setContentComment(?string $ContentComment): self
    {
        $this->ContentComment = $ContentComment;

        return $this;
    }

    public function getItemBrandName(): ?string
    {
        return $this->ItemBrandName;
    }

    public function setItemBrandName(?string $ItemBrandName): self
    {
        $this->ItemBrandName = $ItemBrandName;

        return $this;
    }

    public function getItemGroupeName(): ?string
    {
        return $this->ItemGroupeName;
    }

    public function setItemGroupeName(?string $ItemGroupeName): self
    {
        $this->ItemGroupeName = $ItemGroupeName;

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
            $quoteOrderDetail->setItem($this);
        }

        return $this;
    }

    public function removeQuoteOrderDetail(QuoteOrderDetail $quoteOrderDetail): self
    {
        if ($this->quoteOrderDetails->contains($quoteOrderDetail)) {
            $this->quoteOrderDetails->removeElement($quoteOrderDetail);
            // set the owning side to null (unless already changed)
            if ($quoteOrderDetail->getItem() === $this) {
                $quoteOrderDetail->setItem(null);
            }
        }

        return $this;
    }
}
