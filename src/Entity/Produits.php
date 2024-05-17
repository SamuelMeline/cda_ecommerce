<?php

namespace App\Entity;

use App\Traits\DateTrait;
use ApiPlatform\Metadata\Get;
use Doctrine\DBAL\Types\Types;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\ProduitsRepository;
use ApiPlatform\Metadata\GetCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


#[ORM\Entity(repositoryClass: ProduitsRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[UniqueEntity(fields: ['name'], message: 'Ce produit existe déjà')]
#[ApiResource(operations: [new Get(normalizationContext: ['groups' => 'produits:item']), new GetCollection(normalizationContext: ['groups' => 'add:list'])])]
class Produits
{
    use DateTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['produits:item', 'add:list'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 255, minMessage: 'Le nom doit contenir au moins 3 caractères', maxMessage: 'Le nom doit contenir au plus 255 caractères')]
    #[Groups(['produits:item', 'add:list'])]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 10, minMessage: 'La description doit contenir au moins 10 caractères')]
    #[Groups(['produits:item', 'add:list'])]
    private ?string $description = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\PositiveOrZero]
    #[Groups(['produits:item', 'add:list'])]
    private ?float $price = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(['produits:item', 'add:list'])]
    private ?string $slug = null;


    #[ORM\OneToOne(targetEntity: Reference::class, inversedBy: 'produits', cascade: ['persist', 'remove'])]
    #[Groups(['produits:item', 'add:list'])]
    private ?Reference $reference = null;

    #[ORM\ManyToOne(inversedBy: 'produits')]
    #[Groups(['produits:item', 'add:list'])]
    private ?Categories $categorie = null;

    /**
     * @var Collection<int, Distributeurs>
     */
    #[ORM\ManyToMany(targetEntity: Distributeurs::class, inversedBy: 'produits')]
    #[Groups(['produits:item', 'add:list'])]
    private Collection $distributeur;

    #[ORM\ManyToOne(inversedBy: 'produits')]
    private ?User $user = null;

    /**
     * @var Collection<int, Commentaires>
     */
    #[ORM\OneToMany(targetEntity: Commentaires::class, mappedBy: 'produits')]
    private Collection $commentaire;

    /**
     * @var Collection<int, CommandeDetails>
     */
    #[ORM\OneToMany(targetEntity: CommandeDetails::class, mappedBy: 'produits')]
    private Collection $commandeDetails;

    /**
     * @var Collection<int, Photos>
     */
    #[ORM\OneToMany(targetEntity: Photos::class, mappedBy: 'produits', cascade: ['persist', 'remove'])]
    private Collection $photos;

    public function __construct()
    {
        $this->distributeur = new ArrayCollection();
        $this->commentaire = new ArrayCollection();
        $this->commandeDetails = new ArrayCollection();
        $this->photos = new ArrayCollection();
    }

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function __toString(): string
    {
        // Retourner une représentation textuelle de l'objet, par exemple le nom du distributeur
        return $this->name; // Supposons que 'nom' est le nom du distributeur
    }

    public function getReference(): ?Reference
    {
        return $this->reference;
    }

    public function setReference(?Reference $reference): static
    {
        $this->reference = $reference;

        return $this;
    }

    public function getCategorie(): ?Categories
    {
        return $this->categorie;
    }

    public function setCategorie(?Categories $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * @return Collection<int, Distributeurs>
     */
    public function getDistributeur(): Collection
    {
        return $this->distributeur;
    }

    public function addDistributeur(Distributeurs $distributeur): static
    {
        if (!$this->distributeur->contains($distributeur)) {
            $this->distributeur->add($distributeur);
        }

        return $this;
    }

    public function removeDistributeur(Distributeurs $distributeur): static
    {
        $this->distributeur->removeElement($distributeur);

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
     * @return Collection<int, Commentaires>
     */
    public function getCommentaire(): Collection
    {
        return $this->commentaire;
    }

    public function addCommentaire(Commentaires $commentaire): static
    {
        if (!$this->commentaire->contains($commentaire)) {
            $this->commentaire->add($commentaire);
            $commentaire->setProduits($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaires $commentaire): static
    {
        if ($this->commentaire->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getProduits() === $this) {
                $commentaire->setProduits(null);
            }
        }

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
            $commandeDetail->setProduits($this);
        }

        return $this;
    }

    public function removeCommandeDetail(CommandeDetails $commandeDetail): static
    {
        if ($this->commandeDetails->removeElement($commandeDetail)) {
            // set the owning side to null (unless already changed)
            if ($commandeDetail->getProduits() === $this) {
                $commandeDetail->setProduits(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Photos>
     */
    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    public function addPhoto(Photos $photo): static
    {
        if (!$this->photos->contains($photo)) {
            $this->photos->add($photo);
            $photo->setProduits($this);
        }

        return $this;
    }

    public function removePhoto(Photos $photo): static
    {
        if ($this->photos->removeElement($photo)) {
            // set the owning side to null (unless already changed)
            if ($photo->getProduits() === $this) {
                $photo->setProduits(null);
            }
        }

        return $this;
    }

}
