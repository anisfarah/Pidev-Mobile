<?php

namespace App\Entity;
use App\Repository\ThemeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: ThemeRepository::class)]
class Theme
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private $idTheme;

    #[ORM\Column(length: 25)]
    #[Assert\NotBlank(message:"Le nom du theme est obligatoire.")]
    #[Assert\Length(max:25,maxMessage:"Le nom du thème ne peut pas dépasser {{ limit }} caractères.")]
    private ?string $nomTheme=null;

    #[ORM\OneToMany(mappedBy: 'idTheme', targetEntity: Event::class)]
    private Collection $events;

    #[ORM\Column(length: 300)]
    #[Assert\Length(max:300,min:10,maxMessage:"La description du thème ne peut pas dépasser {{ limit }} caractères.",minMessage:
    "La description du thème doit dépasser {{ limit }} caractères.")]
    private ?string $description = null;

    public function __construct()
    {
        $this->events = new ArrayCollection();
       
    }

    /**
     * @return Collection<int, Event>
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function getIdTheme(): ?int
    {
        return $this->idTheme;
    }

    public function getNomTheme(): ?string
    {
        return $this->nomTheme;
    }

    public function setNomTheme(string $nomTheme): self
    {
        $this->nomTheme = $nomTheme;

        return $this;
    }

    public function addEvent(Event $event): self
    {
        if (!$this->events->contains($event)) {
            $this->events->add($event);
            $event->setIdTheme($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getIdTheme() === $this) {
                $event->setIdTheme(null);
            }
        }

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function __toString()
    {
        return $this->nomTheme;
    }


}
