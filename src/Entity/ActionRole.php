<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ActionRoleRepository")
 */
class ActionRole
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Action", inversedBy="actionRoles")
     */
    private $Action;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Role", inversedBy="actionRoles")
     */
    private $Role;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Privilege", inversedBy="actionRoles")
     */
    private $Privilege;

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

    public function getRole(): ?Role
    {
        return $this->Role;
    }

    public function setRole(?Role $Role): self
    {
        $this->Role = $Role;

        return $this;
    }

    public function getPrivilege(): ?Privilege
    {
        return $this->Privilege;
    }

    public function setPrivilege(?Privilege $Privilege): self
    {
        $this->Privilege = $Privilege;

        return $this;
    }
}
