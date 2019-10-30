<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RoleRepository")
 */
class Role
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
     * @ORM\ManyToMany(targetEntity="App\Entity\Action", inversedBy="roles")
     */
    private $Actions;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Agent", mappedBy="Roles")
     */
    private $agents;

    public function __construct()
    {
        $this->Actions = new ArrayCollection();
        $this->agents = new ArrayCollection();
    }

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

    /**
     * @return Collection|Action[]
     */
    public function getActions(): Collection
    {
        return $this->Actions;
    }

    public function addActions(Action $Actions): self
    {
        if (!$this->Actions->contains($Actions)) {
            $this->Actions[] = $Actions;
        }

        return $this;
    }

    public function removeActions(Action $Actions): self
    {
        if ($this->Actions->contains($Actions)) {
            $this->Actions->removeElement($Actions);
        }

        return $this;
    }

    /**
     * @return Collection|Agent[]
     */
    public function getAgents(): Collection
    {
        return $this->agents;
    }

    public function addAgent(Agent $agent): self
    {
        if (!$this->agents->contains($agent)) {
            $this->agents[] = $agent;
            $agent->addRole($this);
        }

        return $this;
    }

    public function removeAgent(Agent $agent): self
    {
        if ($this->agents->contains($agent)) {
            $this->agents->removeElement($agent);
            $agent->removeRole($this);
        }

        return $this;
    }

}
