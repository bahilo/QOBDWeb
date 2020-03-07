<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PaysRepository")
 */
class Pays
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
    private $Code;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\EanCode", mappedBy="Pays")
     */
    private $eanCodes;

    public function __construct()
    {
        $this->eanCodes = new ArrayCollection();
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

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

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
            $eanCode->setPays($this);
        }

        return $this;
    }

    public function removeEanCode(EanCode $eanCode): self
    {
        if ($this->eanCodes->contains($eanCode)) {
            $this->eanCodes->removeElement($eanCode);
            // set the owning side to null (unless already changed)
            if ($eanCode->getPays() === $this) {
                $eanCode->setPays(null);
            }
        }

        return $this;
    }
}
