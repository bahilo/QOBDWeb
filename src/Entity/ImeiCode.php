<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ImeiCodeRepository")
 */
class ImeiCode
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\EanCode", inversedBy="imeiCodes")
     */
    private $EanCode;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Code;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $SerieCode;

    /**
     * @Groups({"class_property"})
     * @SerializedName("Ean")
     */
    private $Ean;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Item", mappedBy="ImeiCode", cascade={"persist", "remove"})
     */
    private $item;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEanCode(): ?EanCode
    {
        return $this->EanCode;
    }

    public function setEanCode(?EanCode $EanCode): self
    {
        $this->EanCode = $EanCode;

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

    public function getSerieCode(): ?string
    {
        return $this->SerieCode;
    }

    public function setSerieCode(?string $SerieCode): self
    {
        $this->SerieCode = $SerieCode;

        return $this;
    }

    public function getEan(): ?string
    {
        return $this->Ean;
    }

    public function setEan(?string $Ean): self
    {
        $this->Ean = $Ean;

        return $this;
    }

    public function getItem(): ?Item
    {
        return $this->item;
    }

    public function setItem(?Item $item): self
    {
        $this->item = $item;

        // set (or unset) the owning side of the relation if necessary
        $newImeiCode = null === $item ? null : $this;
        if ($item->getImeiCode() !== $newImeiCode) {
            $item->setImeiCode($newImeiCode);
        }

        return $this;
    }
}
