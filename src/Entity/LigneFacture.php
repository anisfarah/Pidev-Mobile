<?php

namespace App\Entity;
use App\Repository\LigneFactureRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LigneFactureRepository::class)]
class LigneFacture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idLigneFac=null;

    #[ORM\Column]
    private ?float $mnt=null;

    #[ORM\Column]
    private ?int $qte=null;

    
    #[ORM\ManyToOne(targetEntity: Utilisateur::class, inversedBy: 'lignefactures')]
    #[ORM\JoinColumn(name: 'id_user', referencedColumnName: 'id_user')]
    private ?Utilisateur $idUser=null;

    
    #[ORM\ManyToOne(targetEntity: Facture::class, inversedBy: 'lignefactures')]
    #[ORM\JoinColumn(name: 'id_facture', referencedColumnName: 'id_facture')]
    private ?Facture $idFacture=null;

  
    #[ORM\ManyToOne(targetEntity: Livre::class, inversedBy: 'lignefactures')]
    #[ORM\JoinColumn(name: 'id_livre', referencedColumnName: 'id_livre')]
    private ?Livre $idLivre=null;

    public function getIdLigneFac(): ?int 
    {
        return $this->idLigneFac;
    }

    public function getMnt(): ?float
    {
        return $this->mnt;
    }

    public function setMnt(float $mnt): self
    {
        $this->mnt = $mnt;

        return $this;
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

    public function getIdUser(): ?Utilisateur
    {
        return $this->idUser;
    }

    public function setIdUser(?Utilisateur $idUser): self
    {
        $this->idUser = $idUser;

        return $this;
    }

    public function getIdFacture(): ?Facture
    {
        return $this->idFacture;
    }

    public function setIdFacture(?Facture $idFacture): self
    {
        $this->idFacture = $idFacture;

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


}
