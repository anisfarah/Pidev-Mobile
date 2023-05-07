<?php

namespace App\Entity;
use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idEvent=null;

    
    #[ORM\Column(length: 30)]
    #[Assert\NotBlank(message:"Le nom de l'événement est obligatoire.")]
    #[Assert\Length(max:25,maxMessage:"Le nom de l'événement ne peut pas dépasser {{ limit }} caractères.")]
    private ?string $nomEvent=null;

   
    #[ORM\Column(length: 25)]
    #[Assert\NotBlank(message:"Le lieu de l'événement est obligatoire.")]
    #[Assert\Length(max:25,maxMessage:"Le lieu de l'événement ne peut pas dépasser {{ limit }} caractères.")]
    private ?string $lieuEvent=null;

    
    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private  $dateEvent=null;

    #[ORM\Column]
    #[Assert\NotBlank(message:"Le prix de l'événement est obligatoire.")]
    #[Assert\PositiveOrZero(message:"Le prix de l'événement doit être un nombre positif ou nulle.")]
    private ?float $prixEvent=null;

   
    #[ORM\Column(length: 25)]
    #[Assert\Length(max:25,maxMessage:"La description de l'événement ne peut pas dépasser {{ limit }} caractères.")]
    private ?string $descEvent=null;

    
    #[ORM\Column(length: 250)]
    #[Assert\Image(maxSize:"5M", maxSizeMessage:"L'image ne doit pas dépasser {{ limit }}.")]
    private ?string $image=null;

   
    #[ORM\ManyToOne(targetEntity: Theme::class, inversedBy: 'events')]
    #[ORM\JoinColumn(name: 'id_theme', referencedColumnName: 'id_theme')]
    private ?Theme $idTheme=null;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class, inversedBy: 'events')]
    #[ORM\JoinColumn(name: 'id_user', referencedColumnName: 'id_user')]
    private ?Utilisateur $idUser=null;

    //inverse
    #[ORM\OneToMany(mappedBy: 'idEvent', targetEntity: Participation::class)]
    private Collection $participations;

    #[ORM\OneToMany(mappedBy: 'idEvent', targetEntity: Favoris::class)]
    private Collection $favoris;

    public function __construct()
    {
        $this->participations = new ArrayCollection();
        $this->favoris = new ArrayCollection();

    }

    /**
     * @return Collection<int, Participation>
     */
    public function getParticipations(): Collection
    {
        return $this->participations;
    }

    public function getIdEvent(): ?int
    {
        return $this->idEvent;
    }

    public function getNomEvent(): ?string
    {
        return $this->nomEvent;
    }

    public function setNomEvent(string $nomEvent): self
    {
        $this->nomEvent = $nomEvent;

        return $this;
    }

    public function getLieuEvent(): ?string
    {
        return $this->lieuEvent;
    }

    public function setLieuEvent(string $lieuEvent): self
    {
        $this->lieuEvent = $lieuEvent;

        return $this;
    }

    public function getDateEvent(): ?\DateTimeInterface
    {
        return $this->dateEvent;
    }

    public function setDateEvent(\DateTimeInterface $dateEvent): self
    {
        $this->dateEvent = $dateEvent;

        return $this;
    }

    public function getPrixEvent(): ?float
    {
        return $this->prixEvent;
    }

    public function setPrixEvent(float $prixEvent): self
    {
        $this->prixEvent = $prixEvent;

        return $this;
    }

    public function getDescEvent(): ?string
    {
        return $this->descEvent;
    }

    public function setDescEvent(string $descEvent): self
    {
        $this->descEvent = $descEvent;

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

    public function getIdTheme(): ?Theme
    {
        return $this->idTheme;
    }

    public function setIdTheme(?Theme $idTheme): self
    {
        $this->idTheme = $idTheme;

        return $this;
    }

    public function getIdUser(): ?Utilisateur
    {
        return $this->idUser;
    }

    public function setIdUser(?Utilisateur $idUser): self
    {
        $this->idUser = $idUser;

        return $this;
    }

    public function addParticipation(Participation $participation): self
    {
        if (!$this->participations->contains($participation)) {
            $this->participations->add($participation);
            $participation->setIdEvent($this);
        }

        return $this;
    }

    public function removeParticipation(Participation $participation): self
    {
        if ($this->participations->removeElement($participation)) {
            // set the owning side to null (unless already changed)
            if ($participation->getIdEvent() === $this) {
                $participation->setIdEvent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Favoris>
     */
    public function getFavoris(): Collection
    {
        return $this->favoris;
    }

    public function addFavori(Favoris $favori): self
    {
        if (!$this->favoris->contains($favori)) {
            $this->favoris->add($favori);
            $favori->setIdEvent($this);
        }

        return $this;
    }

    public function removeFavori(Favoris $favori): self
    {
        if ($this->favoris->removeElement($favori)) {
            // set the owning side to null (unless already changed)
            if ($favori->getIdEvent() === $this) {
                $favori->setIdEvent(null);
            }
        }

        return $this;
    }



}
