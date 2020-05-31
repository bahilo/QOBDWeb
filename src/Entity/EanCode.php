<?php

namespace App\Entity;

use App\Entity\Country;
use App\Entity\Currency;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\Collection;
use JMS\Serializer\Annotation\SerializedName;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EanCodeRepository")
 */
class EanCode
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
     * @SerializedName("Code")
     */
    private $Code;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ImeiCode", mappedBy="EanCode")
     * @Groups({"class_relation"})
     * @SerializedName("imeiCodes")
     */
    private $imeiCodes;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Country", inversedBy="eanCodes")
     * @Groups({"class_property"})
     * @SerializedName("Country")
     */
    private $Country;

    public function __toString()
    {
        return $this->getCode();
    }


    public function __construct()
    {
        $this->imeiCodes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->Code;
    }

    public function setCode(string $Code): self
    {
        $this->Code = $Code;

        return $this;
    }

    /**
     * @return Collection|ImeiCode[]
     */
    public function getImeiCodes(): Collection
    {
        return $this->imeiCodes;
    }

    public function addImeiCode(ImeiCode $imeiCode): self
    {
        if (!$this->imeiCodes->contains($imeiCode)) {
            $this->imeiCodes[] = $imeiCode;
            $imeiCode->setEanCode($this);
        }

        return $this;
    }

    public function removeImeiCode(ImeiCode $imeiCode): self
    {
        if ($this->imeiCodes->contains($imeiCode)) {
            $this->imeiCodes->removeElement($imeiCode);
            // set the owning side to null (unless already changed)
            if ($imeiCode->getEanCode() === $this) {
                $imeiCode->setEanCode(null);
            }
        }

        return $this;
    }

    public function getCountry(): ?Country
    {
        return $this->Country;
    }

    public function setCountry(?Country $Country): self
    {
        $this->Country = $Country;

        return $this;
    }

}
