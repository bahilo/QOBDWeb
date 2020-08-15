<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\SerializedName;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CountryRepository")
 */
class Country
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
     * @SerializedName("Name")
     */
    private $Name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"class_property"})
     * @SerializedName("Culture")
     */
    private $Culture;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\EanCode", mappedBy="Country")
     */
    private $eanCodes;

    /**
     * @ORM\OneToMany(targetEntity=Address::class, mappedBy="Country")
     */
    private $addresses;

    public function __construct()
    {
        $this->eanCodes = new ArrayCollection();
        $this->addresses = new ArrayCollection();
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

    public function getCulture(): ?string
    {
        return $this->Culture;
    }

    public function setCulture(?string $Culture): self
    {
        $this->Culture = $Culture;

        return $this;
    }

    /**
     * @return Collection|EanCode[]
     */
    public function getEanCodes(): Collection
    {
        return $this->eanCodes;
    }

    public function addEanCode(EanCode $eanCode): self
    {
        if (!$this->eanCodes->contains($eanCode)) {
            $this->eanCodes[] = $eanCode;
            $eanCode->setCountry($this);
        }

        return $this;
    }

    public function removeEanCode(EanCode $eanCode): self
    {
        if ($this->eanCodes->contains($eanCode)) {
            $this->eanCodes->removeElement($eanCode);
            // set the owning side to null (unless already changed)
            if ($eanCode->getCountry() === $this) {
                $eanCode->setCountry(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Address[]
     */
    public function getAddresses(): Collection
    {
        return $this->addresses;
    }

    public function addAddress(Address $address): self
    {
        if (!$this->addresses->contains($address)) {
            $this->addresses[] = $address;
            $address->setCountry($this);
        }

        return $this;
    }

    public function removeAddress(Address $address): self
    {
        if ($this->addresses->contains($address)) {
            $this->addresses->removeElement($address);
            // set the owning side to null (unless already changed)
            if ($address->getCountry() === $this) {
                $address->setCountry(null);
            }
        }

        return $this;
    }
}
