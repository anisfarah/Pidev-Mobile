<?php

namespace App\Entity;
use App\Repository\LignePanierRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LignePanierRepository::class)]
class LignePanier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idLigne=null;

    #[ORM\Column]
    private ?int $qte=null;

    
    #[ORM\ManyToOne(targetEntity: Livre::class, inversedBy: 'lignepaniers')]
    #[ORM\JoinColumn(name: 'id_livre', referencedColumnName: 'id_livre')]
    private ?Livre $idLivre=null;

    
    #[ORM\ManyToOne(targetEntity: Panier::class, inversedBy: 'lignepaniers')]
    #[ORM\JoinColumn(name: 'id_panier', referencedColumnName: 'id_panier')]
    private ?Panier $idPanier=null;

    public function getIdLigne(): ?int
    {
        return $this->idLigne;
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

    public function getIdLivre(): ?Livre
    {
        return $this->idLivre;
    }

    public function setIdLivre(?Livre $idLivre): self
    {
        $this->idLivre = $idLivre;

        return $this;
    }

    public function getIdPanier(): ?Panier
    {
        return $this->idPanier;
    }

    public function setIdPanier(?Panier $idPanier): self
    {
        $this->idPanier = $idPanier;

        return $this;
    }


}
