<?php

namespace App\Entity;

use App\Traits\DateTrait;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CommentairesRepository;
use DateTimeImmutable;

#[ORM\Entity(repositoryClass: CommentairesRepository::class)]
class Commentaires
{
    use DateTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'commentaire')]
    private ?Produits $produits = null;

    #[ORM\Column(type: "string", length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: "text")]
    private ?string $content = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduits(): ?Produits
    {
        return $this->produits;
    }

    public function getName() {
        return $this->name;
    }

    public function getContent() {
        return $this->content;
    }    

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setContent($content) {
        $this->content = $content;
    }

    public function setProduits(?Produits $produits): static
    {
        $this->produits = $produits;

        return $this;
    }
}
