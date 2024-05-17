<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use Symfony\Component\Serializer\Annotation\Groups;

use App\Repository\ReferencesRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: ReferencesRepository::class)]
#[ApiResource(operations: [new Get(normalizationContext: ['groups' => 'produits:item']), new GetCollection(normalizationContext: ['groups' => 'add:list'])])]
class Reference
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['produits:item', 'add:list'])]
    private ?string $name = null;

    #[ORM\OneToOne(targetEntity: Produits::class, mappedBy: 'reference')]
    private Produits $produits;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function __toString(): string
    {
        // Retourner une représentation textuelle de l'objet, par exemple le nom du distributeur
        return $this->name; // Supposons que 'nom' est le nom du distributeur
    }

    public function getProduits(): Produits
    {
        return $this->produits;
    }

    public function setProduits(Produits $produits): self
    {
        $this->produits = $produits;

        return $this;
    }
}
