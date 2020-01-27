<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\Collection;
use JMS\Serializer\Annotation\SerializedName;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MessageRepository")
 */
class Message
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
     * @SerializedName("Content")
     */
    private $Content;

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
     * @SerializedName("PathAvatarDir")
     */
    private $PathAvatarDir;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Discussion", inversedBy="messages")
     * @Groups({"class_relation"})
     */
    private $Discussion;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Agent", inversedBy="messages")
     * @Groups({"class_relation", "class_property"})
     * @SerializedName("Agent")
     */
    private $Agent;

    public function __construct()
    {
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

    public function getContent(): ?string
    {
        return $this->Content;
    }

    public function setContent(string $Content): self
    {
        $this->Content = $Content;

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

    public function getAgent(): ?Agent
    {
        return $this->Agent;
    }

    public function setAgent(?Agent $Agent): self
    {
        $this->Agent = $Agent;

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

    public function getCreatedAtShort(): ?string
    {
        return $this->CreatedAtShort;
    }

    public function setCreatedAtShort(?string $CreatedAtShort): self
    {
        $this->CreatedAtShort = $CreatedAtShort;

        return $this;
    }
}
