<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PrivilegeRepository")
 */
class Privilege
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $IsWrite;

    /**
     * @ORM\Column(type="boolean")
     */
    private $IsRead;

    /**
     * @ORM\Column(type="boolean")
     */
    private $IsUpdate;

    /**
     * @ORM\Column(type="boolean")
     */
    private $IsDelete;

    /**
     * @ORM\Column(type="boolean")
     */
    private $IsSendMail;

    /**
     * @ORM\Column(type="datetime")
     */
    private $CreatedAt;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Role", inversedBy="privilege", cascade={"persist", "remove"})
     */
    private $Role;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Action", inversedBy="privilege", cascade={"persist", "remove"})
     */
    private $Action;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIsWrite(): ?bool
    {
        return $this->IsWrite;
    }

    public function setIsWrite(bool $IsWrite): self
    {
        $this->IsWrite = $IsWrite;

        return $this;
    }

    public function getIsRead(): ?bool
    {
        return $this->IsRead;
    }

    public function setIsRead(bool $IsRead): self
    {
        $this->IsRead = $IsRead;

        return $this;
    }

    public function getIsUpdate(): ?bool
    {
        return $this->IsUpdate;
    }

    public function setIsUpdate(bool $IsUpdate): self
    {
        $this->IsUpdate = $IsUpdate;

        return $this;
    }

    public function getIsDelete(): ?bool
    {
        return $this->IsDelete;
    }

    public function setIsDelete(bool $IsDelete): self
    {
        $this->IsDelete = $IsDelete;

        return $this;
    }

    public function getIsSendMail(): ?bool
    {
        return $this->IsSendMail;
    }

    public function setIsSendMail(bool $IsSendMail): self
    {
        $this->IsSendMail = $IsSendMail;

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

    public function getRole(): ?Role
    {
        return $this->Role;
    }

    public function setRole(?Role $Role): self
    {
        $this->Role = $Role;

        return $this;
    }

    public function getAction(): ?Action
    {
        return $this->Action;
    }

    public function setAction(?Action $Action): self
    {
        $this->Action = $Action;

        return $this;
    }
}
