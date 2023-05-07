<?php

namespace App\Entity;
use App\Repository\AvisRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AvisRepository::class)]
class Avis
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer', name: 'id_avis')]
    private ?int $idAvis=null;

   
    #[ORM\Column(length: 16777215)]
    private ?string $messageAvis=null;


    #[ORM\ManyToOne(targetEntity: Utilisateur::class, inversedBy: 'avis')]
    #[ORM\JoinColumn(name: 'id_user', referencedColumnName: 'id_user')]
    private ?Utilisateur $idUser=null;

    public function getIdAvis(): ?int
    {
        return $this->idAvis;
    }

    public function getMessageAvis(): ?string
    {
        return $this->messageAvis;
    }

    public function setMessageAvis(string $messageAvis): self
    {
        $this->messageAvis = $messageAvis;

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
