<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
 */
class Comment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=3000)
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Client", inversedBy="Comment")
     */
    private $client;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Address", mappedBy="Comment", cascade={"persist", "remove"})
     */
    private $address;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\QuoteOrder", mappedBy="PublicComment", cascade={"persist", "remove"})
     */
    private $Order_;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Agent", inversedBy="Comment")
     */
    private $agent;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
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

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

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

    public function getAgent(): ?Agent
    {
        return $this->agent;
    }

    public function setAgent(?Agent $agent): self
    {
        $this->agent = $agent;

        return $this;
    }
}
