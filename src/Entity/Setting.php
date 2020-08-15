<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\SerializedName;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SettingRepository")
 */
class Setting
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
     * @ORM\Column(type="string", length=3000, nullable=true)
     * @Groups({"class_property"})
     * @SerializedName("Value")
     */
    private $Value;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"class_property"})
     * @SerializedName("Code")
     */
    private $Code;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"class_property"})
     * @SerializedName("IsFile")
     */
    private $IsFile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"class_property"})
     * @SerializedName("DisplayName")
     */
    private $DisplayName;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"class_property"})
     * @SerializedName("nRang")
     */
    private $nRang;

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

    public function getValue(): ?string
    {
        return $this->Value;
    }

    public function setValue(?string $Value): self
    {
        $this->Value = $Value;

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

    public function getIsFile(): ?bool
    {
        return $this->IsFile;
    }

    public function setIsFile(?bool $IsFile): self
    {
        $this->IsFile = $IsFile;

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

    public function getNRang(): ?int
    {
        // de mettre en fin de ligne les éléments avec un rang à null
        if(empty($this->nRang))
            return 101010101;            
        return $this->nRang;
    }

    public function setNRang(?int $nRang): self
    {
        $this->nRang = $nRang;

        return $this;
    }
}
