<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ActionRepository")
 */
class Action
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
     * @ORM\Column(type="string", length=255)
     */
    private $DisplayName;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Role", mappedBy="actions")
     */
    private $roles;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Privilege", mappedBy="Action", cascade={"persist", "remove"})
     */
    private $privilege;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\ActionTracker", mappedBy="Action", cascade={"persist", "remove"})
     */
    private $actionTracker;

    public function __construct()
    {
        $this->roles = new ArrayCollection();
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

    public function getDisplayName(): ?string
    {
        return $this->DisplayName;
    }

    public function setDisplayName(string $DisplayName): self
    {
        $this->DisplayName = $DisplayName;

        return $this;
    }

    /**
     * @return Collection|Role[]
     */
    public function getRoles(): Collection
    {
        return $this->roles;
    }

    public function addRole(Role $role): self
    {
        if (!$this->roles->contains($role)) {
            $this->roles[] = $role;
            $role->addRelation($this);
        }

        return $this;
    }

    public function removeRole(Role $role): self
    {
        if ($this->roles->contains($role)) {
            $this->roles->removeElement($role);
            $role->removeRelation($this);
        }

        return $this;
    }

    public function getPrivilege(): ?Privilege
    {
        return $this->privilege;
    }

    public function setPrivilege(?Privilege $privilege): self
    {
        $this->privilege = $privilege;

        // set (or unset) the owning side of the relation if necessary
        $newAction = null === $privilege ? null : $this;
        if ($privilege->getAction() !== $newAction) {
            $privilege->setAction($newAction);
        }

        return $this;
    }

    public function getActionTracker(): ?ActionTracker
    {
        return $this->actionTracker;
    }

    public function setActionTracker(?ActionTracker $actionTracker): self
    {
        $this->actionTracker = $actionTracker;

        // set (or unset) the owning side of the relation if necessary
        $newAction = null === $actionTracker ? null : $this;
        if ($actionTracker->getAction() !== $newAction) {
            $actionTracker->setAction($newAction);
        }

        return $this;
    }
}
