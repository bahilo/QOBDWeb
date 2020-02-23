<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BlogSettingRepository")
 */
class BlogSetting
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
    private $Name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Code;

    /**
     * @ORM\Column(type="string", length=300, nullable=true)
     */
    private $Value;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $IsFile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Link;

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

    public function getCode(): ?string
    {
        return $this->Code;
    }

    public function setCode(string $Code): self
    {
        $this->Code = $Code;

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

    public function getIsFile(): ?bool
    {
        return $this->IsFile;
    }

    public function setIsFile(?bool $IsFile): self
    {
        $this->IsFile = $IsFile;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->Link;
    }

    public function setLink(?string $Link): self
    {
        $this->Link = $Link;

        return $this;
    }
}
