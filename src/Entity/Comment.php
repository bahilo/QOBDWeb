<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\SerializedName;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
 */
class Comment
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
     * @ORM\Column(type="string", length=3000, nullable=true)
     * @Groups({"class_property"})
     * @SerializedName("Content")
     */
    private $content;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createAt;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Address", mappedBy="Comment", cascade={"persist", "remove"})
     */
    private $address;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\QuoteOrder", mappedBy="PublicComment", cascade={"persist", "remove"})
     */
    private $Order_;

    public function __toString()
    {
        return $this->getContent();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->createAt;
    }

    public function setCreateAt(\DateTimeInterface $createAt): self
    {
        $this->createAt = $createAt;

        return $this;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(?Address $address): self
    {
        $this->address = $address;

        // set (or unset) the owning side of the relation if necessary
        $newComment = null === $address ? null : $this;
        if ($address->getComment() !== $newComment) {
            $address->setComment($newComment);
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

        // set (or unset) the owning side of the relation if necessary
        $newPublicComment = null === $Order_ ? null : $this;
        if ($Order_->getPublicComment() !== $newPublicComment) {
            $Order_->setPublicComment($newPublicComment);
        }

        return $this;
    }
}
