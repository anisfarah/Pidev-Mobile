<?php

namespace App\Entity;
use App\Repository\EspaceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EspaceRepository::class)]
class Espace
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idEspace=null;

    #[ORM\Column(length: 25)]
    private ?string $typeEspace=null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateCreation=null;

    public function getIdEspace(): ?int
    {
        return $this->idEspace;
    }

    public function getTypeEspace(): ?string
    {
        return $this->typeEspace;
    }

    public function setTypeEspace(string $typeEspace): self
    {
        $this->typeEspace = $typeEspace;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }


}
