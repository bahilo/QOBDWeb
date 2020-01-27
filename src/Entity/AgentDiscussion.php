<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AgentDiscussionRepository")
 */
class AgentDiscussion
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Agent", inversedBy="agentDiscussions")
     */
    private $agent;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Discussion", inversedBy="agentDiscussions")
     */
    private $discussion;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isCurrent;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isOwner;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $Unread;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAgent(): ?Agent
    {
        return $this->agent;
    }

    public function setAgent(?Agent $agent): self
    {
        $this->agent = $agent;

        return $this;
    }

    public function getDiscussion(): ?Discussion
    {
        return $this->discussion;
    }

    public function setDiscussion(?Discussion $discussion): self
    {
        $this->discussion = $discussion;

        return $this;
    }

    public function getIsCurrent(): ?bool
    {
        return $this->isCurrent;
    }

    public function setIsCurrent(bool $isCurrent): self
    {
        $this->isCurrent = $isCurrent;

        return $this;
    }

    public function getIsOwner(): ?bool
    {
        return $this->isOwner;
    }

    public function setIsOwner(?bool $isOwner): self
    {
        $this->isOwner = $isOwner;

        return $this;
    }

    public function getUnread(): ?int
    {
        return $this->Unread;
    }

    public function setUnread(?int $Unread): self
    {
        $this->Unread = $Unread;

        return $this;
    }
}
