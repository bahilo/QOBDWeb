<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\Collection;
use JMS\Serializer\Annotation\SerializedName;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProviderRepository")
 */
class Provider
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
     * @ORM\OneToMany(targetEntity="App\Entity\Contact", mappedBy="provider")
     */
    private $Contact;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $RIB;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Item", mappedBy="Provider")
     */
    private $items;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"class_property"})
     * @SerializedName("IsEnabled")
     */
    private $IsEnabled;

    public function __construct()
    {
        $this->Contact = new ArrayCollection();
        $this->items = new ArrayCollection();
    }

    public function __tostring(){
        return $this->getName();
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
     * @return Collection|Contact[]
     */
    public function getContact(): Collection
    {
        return $this->Contact;
    }

    public function addContact(Contact $contact): self
    {
        if (!$this->Contact->contains($contact)) {
            $this->Contact[] = $contact;
            $contact->setProvider($this);
        }

        return $this;
    }

    public function removeContact(Contact $contact): self
    {
        if ($this->Contact->contains($contact)) {
            $this->Contact->removeElement($contact);
            // set the owning side to null (unless already changed)
            if ($contact->getProvider() === $this) {
                $contact->setProvider(null);
            }
        }

        return $this;
    }

    public function getRIB(): ?string
    {
        return $this->RIB;
    }

    public function setRIB(?string $RIB): self
    {
        $this->RIB = $RIB;

        return $this;
    }

    /**
     * @return Collection|Item[]
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(Item $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items[] = $item;
            $item->addProvider($this);
        }

        return $this;
    }

    public function removeItem(Item $item): self
    {
        if ($this->items->contains($item)) {
            $this->items->removeElement($item);
            $item->removeProvider($this);
        }

        return $this;
    }

    public function getIsEnabled(): ?bool
    {
        return $this->IsEnabled;
    }

    public function setIsEnabled(bool $IsEnabled): self
    {
        $this->IsEnabled = $IsEnabled;

        return $this;
    }
}
