<?php

namespace App\Entity;
use App\Repository\FactureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FactureRepository::class)]
class Facture
{
    public function getPageNumber()
    {
        return $this->idFacture; // assuming that the ID of the facture is the page number
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idFacture;

    
    #[ORM\Column(length: 25)]
    private ?string $modePaiement=null;

    #[ORM\Column]
    private ?float $mntTotale=null;


    #[ORM\Column(name: "date_fac", type: Types::DATETIME_MUTABLE, nullable: false, options: ["default" => "CURRENT_TIMESTAMP"])]
    private \DateTimeInterface $dateFac;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class, inversedBy: 'factures')]
    #[ORM\JoinColumn(name: 'id_user', referencedColumnName: 'id_user')]
    private ?Utilisateur $idUser=null;




    #[ORM\OneToMany(mappedBy: 'idFacture', targetEntity: LigneFacture::class)]
    private Collection $lignefactures;

    public function __construct()
    {
     
        $this->lignefactures = new ArrayCollection();



    }

     /**
     * @return Collection<int, LigneFacture>
     */
    public function getLignefactures(): Collection
    {
        return $this->lignefactures;
    }



    public function getIdFacture(): ?int
    {
        return $this->idFacture;
    }

    public function getModePaiement(): ?string
    {
        return $this->modePaiement;
    }

    public function setModePaiement(string $modePaiement): self
    {
        $this->modePaiement = $modePaiement;

        return $this;
    }

    public function getMntTotale(): ?float
    {
        return $this->mntTotale;
    }

    public function setMntTotale(float $mntTotale): self
    {
        $this->mntTotale = $mntTotale;

        return $this;
    }

    public function getDateFac(): ?\DateTimeInterface
    {
        return $this->dateFac;
    }

    public function setDateFac(\DateTimeInterface $dateFac): self
    {
        $this->dateFac = $dateFac;

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

    public function addLignefacture(LigneFacture $lignefacture): self
    {
        if (!$this->lignefactures->contains($lignefacture)) {
            $this->lignefactures->add($lignefacture);
            $lignefacture->setIdFacture($this);
        }

        return $this;
    }

    public function removeLignefacture(LigneFacture $lignefacture): self
    {
        if ($this->lignefactures->removeElement($lignefacture)) {
            // set the owning side to null (unless already changed)
            if ($lignefacture->getIdFacture() === $this) {
                $lignefacture->setIdFacture(null);
            }
        }

        return $this;
    }


}
