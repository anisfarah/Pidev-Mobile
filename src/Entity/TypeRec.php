<?php

namespace App\Entity;
use App\Repository\TypeRecRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeRecRepository::class)]
class TypeRec
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idType=null;

    #[ORM\Column(length: 255)]
    private $type;

    #[ORM\OneToMany(mappedBy: 'typeRec', targetEntity: Reclamation::class)]
    private Collection $reclamations;

    public function __construct()
    {
        $this->reclamations = new ArrayCollection();
       
    }

    /**
     * @return Collection<int, Reclamation>
     */
    public function getTypeRec(): Collection
    {
        return $this->reclamations;
    }




    public function getIdType(): ?int
    {
        return $this->idType;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, Reclamation>
     */
    public function getReclamations(): Collection
    {
        return $this->reclamations;
    }

    public function addReclamation(Reclamation $reclamation): self
    {
        if (!$this->reclamations->contains($reclamation)) {
            $this->reclamations->add($reclamation);
            $reclamation->setTypeRec($this);
        }

        return $this;
    }

    public function removeReclamation(Reclamation $reclamation): self
    {
        if ($this->reclamations->removeElement($reclamation)) {
            // set the owning side to null (unless already changed)
            if ($reclamation->getTypeRec() === $this) {
                $reclamation->setTypeRec(null);
            }
        }

        return $this;
    }

    public function __toString(){
        return $this->type;
    }


}
