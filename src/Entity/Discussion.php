<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\Collection;
use JMS\Serializer\Annotation\SerializedName;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DiscussionRepository")
 */
class Discussion
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
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"class_property"})
     * @SerializedName("Name")
     */
    private $Name;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"class_property"})
     * @SerializedName("CreatedAt")
     */
    private $CreatedAt;

    /**
     * @Groups({"class_property"})
     * @SerializedName("CreatedAtShort")
     */
    private $CreatedAtShort;

    /**
     * @Groups({"class_property"})
     * @SerializedName("Owner")
     */
    private $Owner;

    /**
     * @Groups({"class_property"})
     * @SerializedName("PathAvatarDir")
     */
    private $PathAvatarDir;

    /**
     * @Groups({"class_property"})
     * @SerializedName("IsOwner")
     */
    private $IsOwner;

    /**
     * @Groups({"class_property"})
     * @SerializedName("IsCurrent")
     */
    private $IsCurrent;

    /**
     * @Groups({"class_property"})
     * @SerializedName("Recipients")
     */
    private $Recipients;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Message", mappedBy="Discussion")
     * @Groups({"class_relation"})
     */
    private $messages;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\AgentDiscussion", mappedBy="discussion")
     * @Groups({"class_relation"})
     */
    private $agentDiscussions;

    /**
     * @Groups({"class_property"})
     * @SerializedName("TotalUnRead")
     */
    private $TotalUnRead;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
        $this->agentDiscussions = new ArrayCollection();
        $this->Recipients = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(?string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    public function getIsOwner(): ?bool
    {
        return $this->IsOwner;
    }

    public function setIsOwner(?bool $IsOwner): self
    {
        $this->IsOwner = $IsOwner;

        return $this;
    }

    public function getIsCurrent(): ?bool
    {
        return $this->IsCurrent;
    }

    public function setIsCurrent(?bool $IsCurrent): self
    {
        $this->IsCurrent = $IsCurrent;

        return $this;
    }

    public function getCreatedAtShort(): ?string
    {
        return $this->CreatedAtShort;
    }

    public function setCreatedAtShort(?string $CreatedAtShort): self
    {
        $this->CreatedAtShort = $CreatedAtShort;

        return $this;
    }

    public function getOwner(): ?Agent
    {
        return $this->Owner;
    }

    public function setOwner(?Agent $Owner): self
    {
        $this->Owner = $Owner;

        return $this;
    }

    public function getPathAvatarDir(): ?string
    {
        return $this->PathAvatarDir;
    }

    public function setPathAvatarDir(?string $PathAvatarDir): self
    {
        $this->PathAvatarDir = $PathAvatarDir;

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

    /**
     * @return Collection|Message[]
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setDiscussion($this);
        }

        return $this;
    }

    /**
     * @return Collection|Agent[]
     */
    public function getRecipients(): Collection
    {
        return $this->Recipients;
    }

    public function addRecipient(Agent $Recipient): self
    {
        if(empty($this->Recipients))
            $this->Recipients = new ArrayCollection();

        if (!$this->Recipients->contains($Recipient)) {
            $this->Recipients[] = $Recipient;
        }

        return $this;
    }

    public function removeRecipient(Agent $Recipient): self
    {
        if (empty($this->Recipients))
            $this->Recipients = new ArrayCollection();
            
        if ($this->Recipients->contains($Recipient)) {
            $this->Recipients->removeElement($Recipient);
        }

        return $this;
    }

    /**
     * @return Collection|AgentDiscussion[]
     */
    public function getAgentDiscussions(): Collection
    {
        return $this->agentDiscussions;
    }

    public function addAgentDiscussion(AgentDiscussion $agentDiscussion): self
    {
        if (!$this->agentDiscussions->contains($agentDiscussion)) {
            $this->agentDiscussions[] = $agentDiscussion;
            $agentDiscussion->setDiscussion($this);
        }

        return $this;
    }

    public function removeAgentDiscussion(AgentDiscussion $agentDiscussion): self
    {
        if ($this->agentDiscussions->contains($agentDiscussion)) {
            $this->agentDiscussions->removeElement($agentDiscussion);
            // set the owning side to null (unless already changed)
            if ($agentDiscussion->getDiscussion() === $this) {
                $agentDiscussion->setDiscussion(null);
            }
        }

        return $this;
    }

    public function getTotalUnRead(): ?int
    {
        return $this->TotalUnRead;
    }

    public function setTotalUnRead(int $TotalUnRead): self
    {
        $this->TotalUnRead = $TotalUnRead;

        return $this;
    }
}
