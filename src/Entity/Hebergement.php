<?php

namespace App\Entity;

use App\Repository\HebergementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=HebergementRepository::class)
 */
class Hebergement
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $emailClient;

    /**
     * @ORM\OneToMany(targetEntity=DomaineName::class, mappedBy="hebergement")
     */
    private $DomaineName;

    public function __construct()
    {
        $this->DomaineName = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getEmailClient(): ?string
    {
        return $this->emailClient;
    }

    public function setEmailClient(string $emailClient): self
    {
        $this->emailClient = $emailClient;

        return $this;
    }

    /**
     * @return Collection|DomaineName[]
     */
    public function getDomaineName(): Collection
    {
        return $this->DomaineName;
    }

    public function addDomaineName(DomaineName $domaineName): self
    {
        if (!$this->DomaineName->contains($domaineName)) {
            $this->DomaineName[] = $domaineName;
            $domaineName->setHebergement($this);
        }

        return $this;
    }

    public function removeDomaineName(DomaineName $domaineName): self
    {
        if ($this->DomaineName->removeElement($domaineName)) {
            // set the owning side to null (unless already changed)
            if ($domaineName->getHebergement() === $this) {
                $domaineName->setHebergement(null);
            }
        }

        return $this;
    }
}
