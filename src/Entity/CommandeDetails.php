<?php

namespace App\Entity;

use App\Repository\CommandeDetailsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeDetailsRepository::class)]
class CommandeDetails
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $quantite = null;

    #[ORM\Column]
    private ?float $prix = null;

    #[ORM\ManyToOne(inversedBy: 'commandeDetails')]
    private ?Commandes $commandes = null;

    #[ORM\ManyToOne(inversedBy: 'commandeDetails')]
    private ?Produits $produits = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getCommandes(): ?Commandes
    {
        return $this->commandes;
    }

    public function setCommandes(?Commandes $commandes): static
    {
        $this->commandes = $commandes;

        return $this;
    }

    public function getProduits(): ?Produits
    {
        return $this->produits;
    }

    public function setProduits(?Produits $produits): static
    {
        $this->produits = $produits;

        return $this;
    }
}
