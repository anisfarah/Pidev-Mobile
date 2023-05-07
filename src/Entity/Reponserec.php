<?php

namespace App\Entity;
use App\Repository\ReponserecRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReponserecRepository::class)]
class Reponserec
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idReponse=null;

    
    #[ORM\Column(length: 1000)]
    private ?string $contenu=null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $daterep=null;

    #[ORM\ManyToOne(targetEntity: Reclamation::class, inversedBy: 'reponserecs')]
    #[ORM\JoinColumn(name: 'id_reclamation', referencedColumnName: 'id_rec')]
    private ?Reclamation $idReclamation=null;

    

    public function getIdReponse(): ?int
    {
        return $this->idReponse;
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

    public function getDaterep(): ?\DateTimeInterface
    {
        return $this->daterep;
    }

    public function setDaterep(\DateTimeInterface $daterep): self
    {
        $this->daterep = $daterep;

        return $this;
    }

    public function getIdReclamation(): ?Reclamation
    {
        return $this->idReclamation;
    }

    public function setIdReclamation(?Reclamation $idReclamation): self
    {
        $this->idReclamation = $idReclamation;

        return $this;
    }


}
