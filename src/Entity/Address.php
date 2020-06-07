<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\SerializedName;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AddressRepository")
 */
class Address
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
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"class_property"})
     * @SerializedName("AddressName")
     */
    private $Name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $DisplayName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message = "La ville ne peux pas être vide")
     * @Groups({"class_property"})
     * @SerializedName("City") 
     */
    private $City;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message = "La rue ne peux pas être vide")
     * @Groups({"class_property"})
     * @SerializedName("Street") 
     */
    private $Street;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message = "Le code postal ne peux pas être vide")
     * @Groups({"class_property"})
     * @SerializedName("ZipCode") 
     */
    private $ZipCode;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message = "Le pays ne peux pas être vide")
     * @Groups({"class_property"})
     * @SerializedName("Country") 
     */
    private $Country;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Comment", inversedBy="address", cascade={"persist", "remove"})
     * @SerializedName("Country") 
     */
    private $Comment;
    

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $IsPrincipal;

    private $ContentComment;


    public function __construct()
    {
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDisplayName(): ?string
    {
        return $this->DisplayName;
    }

    public function setDisplayName(?string $DisplayName): self
    {
        $this->DisplayName = $DisplayName;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->City;
    }

    public function setCity(string $City): self
    {
        $this->City = $City;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->Street;
    }

    public function setStreet(string $Street): self
    {
        $this->Street = $Street;

        return $this;
    }

    public function getZipCode(): ?string
    {
        return $this->ZipCode;
    }

    public function setZipCode(string $ZipCode): self
    {
        $this->ZipCode = $ZipCode;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->Country;
    }

    public function setCountry(string $Country): self
    {
        $this->Country = $Country;

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

    public function getIsPrincipal(): ?bool
    {
        return $this->IsPrincipal;
    }

    public function setIsPrincipal(?bool $IsPrincipal): self
    {
        $this->IsPrincipal = $IsPrincipal;

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
}
