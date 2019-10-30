<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LicenseRepository")
 */
class License
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
    private $AppVersion;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Company;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $HashedKey;

    /**
     * @ORM\Column(type="boolean")
     */
    private $IsEnable;

    /**
     * @ORM\Column(type="datetime")
     */
    private $CreatedAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $EndDateAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAppVersion(): ?string
    {
        return $this->AppVersion;
    }

    public function setAppVersion(string $AppVersion): self
    {
        $this->AppVersion = $AppVersion;

        return $this;
    }

    public function getCompany(): ?string
    {
        return $this->Company;
    }

    public function setCompany(string $Company): self
    {
        $this->Company = $Company;

        return $this;
    }

    public function getHashedKey(): ?string
    {
        return $this->HashedKey;
    }

    public function setHashedKey(string $HashedKey): self
    {
        $this->HashedKey = $HashedKey;

        return $this;
    }

    public function getIsEnable(): ?bool
    {
        return $this->IsEnable;
    }

    public function setIsEnable(bool $IsEnable): self
    {
        $this->IsEnable = $IsEnable;

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

    public function getEndDateAt(): ?\DateTimeInterface
    {
        return $this->EndDateAt;
    }

    public function setEndDateAt(\DateTimeInterface $EndDateAt): self
    {
        $this->EndDateAt = $EndDateAt;

        return $this;
    }
}
