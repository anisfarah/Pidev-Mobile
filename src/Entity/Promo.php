<?php

namespace App\Entity;
use App\Repository\PromoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: PromoRepository::class)]
class Promo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id=null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Assert\NotNull(message:"La date de fin est obligatoire.")]
    #[Assert\Expression("value > this.getDateDebut()",message: "La date de fin doit être supérieure à la date de début.")]
    private ?\DateTimeInterface $dateFin=null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Le code est obligatoire .")]
    private ?string $code=null;

    #[ORM\Column]
    #[Assert\NotBlank(message:"La réduction est obligatoire .")]
    #[Assert\NotNull(message:"La réduction est obligatoire .")]
    #[Assert\Range(min:0, max:100,notInRangeMessage:"La réduction doit etre entre 0 et 100.")]
    private ?float $reduction=null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Assert\NotNull(message:"La date de début est obligatoire.")]
    #[Assert\Expression("value < this.getDateFin()",message: "La date de début doit être inférieure à la date de fin.")]
    private ?\DateTimeInterface $dateDebut=null;


    #[ORM\OneToMany(mappedBy: 'codePromo', targetEntity: Livre::class)]
    private Collection $livres;

    public function __construct()
    {
        $this->livres = new ArrayCollection();

    }

    /**
     * @return Collection<int, Livre>
     */
    public function getLivres(): Collection
    {
        return $this->livres;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getReduction(): ?float
    {
        return $this->reduction;
    }

    public function setReduction(float $reduction): self
    {
        $this->reduction = $reduction;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(?\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function addLivre(Livre $livre): self
    {
        if (!$this->livres->contains($livre)) {
            $this->livres->add($livre);
            $livre->setCodePromo($this);
        }

        return $this;
    }

    public function removeLivre(Livre $livre): self
    {
        if ($this->livres->removeElement($livre)) {
            // set the owning side to null (unless already changed)
            if ($livre->getCodePromo() === $this) {
                $livre->setCodePromo(null);
            }
        }

        return $this;
    }


}