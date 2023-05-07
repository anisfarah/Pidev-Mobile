<?php

namespace App\Entity;
use App\Repository\ReclamationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReclamationRepository::class)]
class Reclamation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idRec=null;

   
    #[ORM\Column(length: 1000)]
    #[Assert\NotNull(message:"Contenu est obligatoire.")]
    private ?string $contenu=null;

    
    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateRec=null;

    
    #[ORM\Column(length: 255)]
    private ?string $etat=null;

    #[ORM\Column(length:2000, nullable: true)]
    private ?string $img=null;

  
    #[ORM\ManyToOne(targetEntity: TypeRec::class, inversedBy: 'reclamations')]
    #[ORM\JoinColumn(name: 'id_type', referencedColumnName: 'id_type')]
    private ?TypeRec $typeRec=null;


    #[ORM\ManyToOne(targetEntity: Utilisateur::class, inversedBy: 'reclamations')]
    #[ORM\JoinColumn(name: 'id_user', referencedColumnName: 'id_user')]
    private ?Utilisateur $idUser=null;


    #[ORM\OneToMany(mappedBy: 'idReclamation', targetEntity: Reponserec::class)]
    private Collection $reponserecs;

    public function __construct()
    {
        $this->reponserecs = new ArrayCollection();

    }

    /**
     * @return Collection<int, Reponserec>
     */
    public function getReponserecs(): Collection
    {
        return $this->reponserecs;
    }

    public function getIdRec(): ?int
    {
        return $this->idRec;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): self
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getDateRec(): ?\DateTimeInterface
    {
        return $this->dateRec;
    }

    public function setDateRec(\DateTimeInterface $dateRec): self
    {
        $this->dateRec = $dateRec;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getImg() : ?String
    {
        return $this->img;
    }

    public function setImg(String $img): self
    {
        $this->img = $img;

        return $this;
    }

    public function getTypeRec(): ?TypeRec
    {
        return $this->typeRec;
    }

    public function setTypeRec(?TypeRec $typeRec): self
    {
        $this->typeRec = $typeRec;

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

    public function addReponserec(Reponserec $reponserec): self
    {
        if (!$this->reponserecs->contains($reponserec)) {
            $this->reponserecs->add($reponserec);
            $reponserec->setIdReclamation($this);
        }

        return $this;
    }

    public function removeReponserec(Reponserec $reponserec): self
    {
        if ($this->reponserecs->removeElement($reponserec)) {
            // set the owning side to null (unless already changed)
            if ($reponserec->getIdReclamation() === $this) {
                $reponserec->setIdReclamation(null);
            }
        }

        return $this;
    }


}
