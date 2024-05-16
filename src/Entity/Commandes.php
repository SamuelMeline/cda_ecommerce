<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CommandesRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: CommandesRepository::class)]
class Commandes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $numero_cmd = null;

    private ?string $created_at = null;

    #[ORM\ManyToOne(inversedBy: 'commandes')]
    private ?User $user = null;

    /**
     * @var Collection<int, CommandeDetails>
     */
    #[ORM\OneToMany(targetEntity: CommandeDetails::class, mappedBy: 'commandes', cascade: ['persist', 'remove'])]
    private Collection $commandeDetails;

    public function __construct()
    {
        $this->commandeDetails = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumeroCmd(): ?string
    {
        return $this->numero_cmd;
    }

    public function setNumeroCmd(string $numero_cmd): static
    {
        $this->numero_cmd = $numero_cmd;

        return $this;
    }

    public function getCreatedAt(): ?string
    {
        return $this->created_at;
    }

    public function setCreatedAt(string $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, CommandeDetails>
     */
    public function getCommandeDetails(): Collection
    {
        return $this->commandeDetails;
    }

    public function addCommandeDetail(CommandeDetails $commandeDetail): static
    {
        if (!$this->commandeDetails->contains($commandeDetail)) {
            $this->commandeDetails->add($commandeDetail);
            $commandeDetail->setCommandes($this);
        }

        return $this;
    }

    public function removeCommandeDetail(CommandeDetails $commandeDetail): static
    {
        if ($this->commandeDetails->removeElement($commandeDetail)) {
            // set the owning side to null (unless already changed)
            if ($commandeDetail->getCommandes() === $this) {
                $commandeDetail->setCommandes(null);
            }
        }

        return $this;
    }
}
