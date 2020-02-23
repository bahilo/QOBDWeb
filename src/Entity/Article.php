<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 */
class Article
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Title;

    /**
     * @ORM\Column(type="string", length=3000)
     */
    private $Short;

    /**
     * @ORM\Column(type="string", length=10000)
     */
    private $Content;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Agent", inversedBy="PublishAt")
     */
    private $Author;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $PublishAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $PublishEndAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $CreatedAt;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $IsCenterOfInterest;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $IsProduct;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $IsPartenaire;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $IsTeam;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $IsTestimony;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Role;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Picture;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->Title;
    }

    public function setTitle(string $Title): self
    {
        $this->Title = $Title;

        return $this;
    }

    public function getShort(): ?string
    {
        return $this->Short;
    }

    public function setShort(string $Short): self
    {
        $this->Short = $Short;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->Content;
    }

    public function setContent(string $Content): self
    {
        $this->Content = $Content;

        return $this;
    }

    public function getAuthor(): ?Agent
    {
        return $this->Author;
    }

    public function setAuthor(?Agent $Author): self
    {
        $this->Author = $Author;

        return $this;
    }

    public function getPublishAt(): ?\DateTimeInterface
    {
        return $this->PublishAt;
    }

    public function setPublishAt(\DateTimeInterface $PublishAt): self
    {
        $this->PublishAt = $PublishAt;

        return $this;
    }

    public function getPublishEndAt(): ?\DateTimeInterface
    {
        return $this->PublishEndAt;
    }

    public function setPublishEndAt(\DateTimeInterface $PublishEndAt): self
    {
        $this->PublishEndAt = $PublishEndAt;

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

    public function getIsCenterOfInterest(): ?bool
    {
        return $this->IsCenterOfInterest;
    }

    public function setIsCenterOfInterest(?bool $IsCenterOfInterest): self
    {
        $this->IsCenterOfInterest = $IsCenterOfInterest;

        return $this;
    }

    public function getIsProduct(): ?bool
    {
        return $this->IsProduct;
    }

    public function setIsProduct(?bool $IsProduct): self
    {
        $this->IsProduct = $IsProduct;

        return $this;
    }

    public function getIsPartenaire(): ?bool
    {
        return $this->IsPartenaire;
    }

    public function setIsPartenaire(?bool $IsPartenaire): self
    {
        $this->IsPartenaire = $IsPartenaire;

        return $this;
    }

    public function getIsTeam(): ?bool
    {
        return $this->IsTeam;
    }

    public function setIsTeam(?bool $IsTeam): self
    {
        $this->IsTeam = $IsTeam;

        return $this;
    }

    public function getIsTestimony(): ?bool
    {
        return $this->IsTestimony;
    }

    public function setIsTestimony(?bool $IsTestimony): self
    {
        $this->IsTestimony = $IsTestimony;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->Role;
    }

    public function setRole(?string $Role): self
    {
        $this->Role = $Role;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(?string $Name): self
    {
        $this->Name = $Name;

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
}
