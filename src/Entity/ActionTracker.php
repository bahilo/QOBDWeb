<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ActionTrackerRepository")
 */
class ActionTracker
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Action", inversedBy="actionTracker", cascade={"persist", "remove"})
     */
    private $Action;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Agent", inversedBy="actionTrackers")
     */
    private $Agent;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getAgent(): ?Agent
    {
        return $this->Agent;
    }

    public function setAgent(?Agent $Agent): self
    {
        $this->Agent = $Agent;

        return $this;
    }
}
