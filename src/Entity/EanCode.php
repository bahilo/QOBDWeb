<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EanCodeRepository")
 */
class EanCode
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Pays", inversedBy="eanCodes")
     */
    private $Pays;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Code;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ImeiCode", mappedBy="EanCode")
     */
    private $imeiCodes;

    public function __construct()
    {
        $this->imeiCodes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPays(): ?Pays
    {
        return $this->Pays;
    }

    public function setPays(?Pays $Pays): self
    {
        $this->Pays = $Pays;

        return $this;
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
}
