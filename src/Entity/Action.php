<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\Collection;
use JMS\Serializer\Annotation\SerializedName;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ActionRepository")
 */
class Action
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
     * @ORM\Column(type="string", length=255)
     * @Groups({"class_property"})
     * @SerializedName("DisplayName")
     */
    private $DisplayName;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\ActionTracker", mappedBy="Action", cascade={"persist", "remove"})
     * @Groups({"class_relation"})
     */
    private $actionTracker;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ActionRole", mappedBy="Action")
     */
    private $actionRoles;


    public function __construct()
    {
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

    public function getDisplayName(): ?string
    {
        return $this->DisplayName;
    }

    public function setDisplayName(string $DisplayName): self
    {
        $this->DisplayName = $DisplayName;

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
            $actionRole->setAction($this);
        }

        return $this;
    }

    public function removeActionRole(ActionRole $actionRole): self
    {
        if ($this->actionRoles->contains($actionRole)) {
            $this->actionRoles->removeElement($actionRole);
            // set the owning side to null (unless already changed)
            if ($actionRole->getAction() === $this) {
                $actionRole->setAction(null);
            }
        }

        return $this;
    }
    
}
