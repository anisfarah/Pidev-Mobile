<?php

namespace App\Entity;
use App\Repository\SousthemeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SousthemeRepository::class)]
class Soustheme
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private $idSoustheme;

    #[ORM\Column(length: 25)]
    private ?string $nomSoustheme;

    public function getIdSoustheme(): ?int
    {
        return $this->idSoustheme;
    }

    public function getNomSoustheme(): ?string
    {
        return $this->nomSoustheme;
    }

    public function setNomSoustheme(string $nomSoustheme): self
    {
        $this->nomSoustheme = $nomSoustheme;

        return $this;
    }


}
