<?php

namespace App\Entity;
use App\Repository\ActualiteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActualiteRepository::class)]
class Actualite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idAct=null;

    #[ORM\Column(length: 255)]
    private ?string $titre=null;

    #[ORM\Column(length: 255)]
    private ?string $descAct=null;

    #[ORM\Column]
    private ?int $nbLike=null;

    #[ORM\Column]
    private ?int $nbCommentaire=null;

    #[ORM\Column(length: 255)]
    private ?string $imageAct=null;

    public function getIdAct(): ?int
    {
        return $this->idAct;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescAct(): ?string
    {
        return $this->descAct;
    }

    public function setDescAct(string $descAct): self
    {
        $this->descAct = $descAct;

        return $this;
    }

    public function getNbLike(): ?int
    {
        return $this->nbLike;
    }

    public function setNbLike(int $nbLike): self
    {
        $this->nbLike = $nbLike;

        return $this;
    }

    public function getNbCommentaire(): ?int
    {
        return $this->nbCommentaire;
    }

    public function setNbCommentaire(int $nbCommentaire): self
    {
        $this->nbCommentaire = $nbCommentaire;

        return $this;
    }

    public function getImageAct(): ?string
    {
        return $this->imageAct;
    }

    public function setImageAct(string $imageAct): self
    {
        $this->imageAct = $imageAct;

        return $this;
    }


}
