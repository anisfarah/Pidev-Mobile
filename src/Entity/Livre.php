<?php

namespace App\Entity;
use App\Repository\LivreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: LivreRepository::class)]
class Livre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer', name: 'id_livre')]
    private ?int $idLivre=null;

    
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Le libellé est obligatoire.")]
    #[Assert\NotNull(message:"Le libellé est obligatoire.")]
    private ?string $libelle=null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:" La description est obligatoire.")]
    private ?string $description=null;

    
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:" L'éditeur est obligatoire.")]
    private ?string $editeur=null;

    
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"La catégorie est obligatoire.")]
    private ?string $categorie=null;

 
    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Assert\NotNull(message:"La date d'édition est obligatoire.")]
    #[Assert\LessThanOrEqual(value:'today',message:"La date d'édition doit être inférieure ou égale à la date actuelle.")]
    private ?\DateTimeInterface $dateEdition=null;

    #[ORM\Column]
    #[Assert\NotBlank(message:"Le prix est obligatoire .")]
    #[Assert\GreaterThanOrEqual(value:0,message:"Le prix doit être supérieur ou égal à 0.")]
    private ?float $prix=null;

   
    #[ORM\Column(length: 25)]
    #[Assert\NotBlank(message:"La langue est obligatoire.")]
    private ?string $langue=null;

    
    #[ORM\Column(length: 1500)]
    //#[Assert\NotBlank(message:"Veuillez sélectionnez une image.")]
    private ?string $image=null;

   
    #[ORM\ManyToOne(targetEntity: Promo::class, inversedBy: 'livres')]
    #[ORM\JoinColumn(name: 'code_promo', referencedColumnName: 'id')]
    private ?Promo $codePromo=null;



    
    #[ORM\ManyToOne(targetEntity: Utilisateur::class, inversedBy: 'livres')]
    #[ORM\JoinColumn(name: 'auteur', referencedColumnName: 'id_user')]
    private ?Utilisateur $auteur=null;


    //inverse 
    #[ORM\OneToMany(mappedBy: 'idLivre', targetEntity: LigneFacture::class)]
    private Collection $lignefactures;

    #[ORM\OneToMany(mappedBy: 'idLivre', targetEntity: LignePanier::class)]
    private Collection $lignepaniers;

    public function __construct()
    {
     
        $this->lignefactures = new ArrayCollection();
        $this->lignepaniers = new ArrayCollection();

    }

     /**
     * @return Collection<int, LigneFacture>
     */
    public function getLignefactures(): Collection
    {
        return $this->lignefactures;
    }
    /**
     * @return Collection<int, LignePanier>
     */
    public function getLignepaniers(): Collection
    {
        return $this->lignepaniers;
    }


    public function getIdLivre(): ?int
    {
        return $this->idLivre;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getEditeur(): ?string
    {
        return $this->editeur;
    }

    public function setEditeur(string $editeur): self
    {
        $this->editeur = $editeur;

        return $this;
    }

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getDateEdition(): ?\DateTimeInterface
    {
        return $this->dateEdition;
    }

    public function setDateEdition(?\DateTimeInterface $dateEdition): self
    {
        $this->dateEdition = $dateEdition;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getLangue(): ?string
    {
        return $this->langue;
    }

    public function setLangue(string $langue): self
    {
        $this->langue = $langue;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getCodePromo(): ?Promo
    {
        return $this->codePromo;
    }

    public function setCodePromo(?Promo $codePromo): self
    {
        $this->codePromo = $codePromo;

        return $this;
    }

    public function getAuteur(): ?Utilisateur
    {
        return $this->auteur;
    }

    public function setAuteur(?Utilisateur $auteur): self
    {
        $this->auteur = $auteur;

        return $this;
    }


}