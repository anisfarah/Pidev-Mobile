<?php

namespace App\Entity;
use App\Repository\PanierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PanierRepository::class)]
class Panier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idPanier=null;

    #[ORM\Column]
    private ?int $qte=null;

    #[ORM\Column]
    private ?float $mntTotal=null;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class, inversedBy: 'paniers')]
    #[ORM\JoinColumn(name: 'id_user', referencedColumnName: 'id_user')]
    private ?Utilisateur $idUser=null;


    #[ORM\OneToMany(mappedBy: 'idPanier', targetEntity: LignePanier::class)]
    private Collection $lignepaniers;

    public function __construct()
    {
     
        $this->lignepaniers = new ArrayCollection();

    }

    /**
     * @return Collection<int, LignePanier>
     */
    public function getLignepaniers(): Collection
    {
        return $this->lignepaniers;
    }


    public function getIdPanier(): ?int
    {
        return $this->idPanier;
    }

    public function getQte(): ?int
    {
        return $this->qte;
    }

    public function setQte(int $qte): self
    {
        $this->qte = $qte;

        return $this;
    }

    public function getMntTotal(): ?float
    {
        return $this->mntTotal;
    }

    public function setMntTotal(float $mntTotal): self
    {
        $this->mntTotal = $mntTotal;

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


}
