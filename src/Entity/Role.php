<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\Collection;
use JMS\Serializer\Annotation\SerializedName;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RoleRepository")
 */
class Role
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
     * @SerializedName("Name")
     */
    private $Name;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Agent", mappedBy="Roles")
     * @Groups({"class_relation"})
     */
    private $agents;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ActionRole", mappedBy="Role")
     * @Groups({"class_relation"})
     */
    private $actionRoles;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"class_property"})
     * @SerializedName("DisplayName")
     */
    private $DisplayName;

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

    public function getDisplayName(): ?string
    {
        return $this->DisplayName;
    }

    public function setDisplayName(?string $DisplayName): self
    {
        $this->DisplayName = $DisplayName;

        return $this;
    }

}
