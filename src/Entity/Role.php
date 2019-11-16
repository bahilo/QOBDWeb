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
     * @ORM\ManyToMany(targetEntity="App\Entity\Agent", mappedBy="Roles")
     */
    private $agents;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ActionRole", mappedBy="Role")
     */
    private $actionRoles;

    public function __construct()
    {
        $this->agents = new ArrayCollection();
        $this->actionRoles = new ArrayCollection();
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

    /**
     * @return Collection|ActionRole[]
     */
    public function getActionRoles(): Collection
    {
        return $this->actionRoles;
    }

    public function addActionRole(ActionRole $actionRole): self
    {
        if (!$this->actionRoles->contains($actionRole)) {
            $this->actionRoles[] = $actionRole;
            $actionRole->setRole($this);
        }

        return $this;
    }

    public function removeActionRole(ActionRole $actionRole): self
    {
        if ($this->actionRoles->contains($actionRole)) {
            $this->actionRoles->removeElement($actionRole);
            // set the owning side to null (unless already changed)
            if ($actionRole->getRole() === $this) {
                $actionRole->setRole(null);
            }
        }

        return $this;
    }

}
