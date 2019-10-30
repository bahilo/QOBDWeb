<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MessageRepository")
 */
class Message
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Discussion", inversedBy="messages")
     */
    private $Discussion;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Agent", inversedBy="messages")
     */
    private $Agent;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Content;

    /**
     * @ORM\Column(type="boolean")
     */
    private $IsRed;

    /**
     * @ORM\Column(type="datetime")
     */
    private $CreatedAt;

    public function __construct()
    {
        $this->Agent = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDiscussion(): ?Discussion
    {
        return $this->Discussion;
    }

    public function setDiscussion(?Discussion $Discussion): self
    {
        $this->Discussion = $Discussion;

        return $this;
    }

    /**
     * @return Collection|Agent[]
     */
    public function getAgent(): Collection
    {
        return $this->Agent;
    }

    public function addAgent(Agent $agent): self
    {
        if (!$this->Agent->contains($agent)) {
            $this->Agent[] = $agent;
        }

        return $this;
    }

    public function removeAgent(Agent $agent): self
    {
        if ($this->Agent->contains($agent)) {
            $this->Agent->removeElement($agent);
        }

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->Content;
    }

    public function setContent(string $Content): self
    {
        $this->Content = $Content;

        return $this;
    }

    public function getIsRed(): ?bool
    {
        return $this->IsRed;
    }

    public function setIsRed(bool $IsRed): self
    {
        $this->IsRed = $IsRed;

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
}
