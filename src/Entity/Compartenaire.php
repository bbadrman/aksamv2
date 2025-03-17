<?php

namespace App\Entity;

use App\Repository\CompartenaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CompartenaireRepository::class)]
class Compartenaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $compagnie = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $partenaire = null;

    /**
     * @var Collection<int, Contrat>
     */
    #[ORM\OneToMany(targetEntity: Contrat::class, mappedBy: 'compagnie')]
    private Collection $contrats;

    public function __construct()
    {
        $this->contrats = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompagnie(): ?string
    {
        return $this->compagnie;
    }

    public function setCompagnie(?string $compagnie): static
    {
        $this->compagnie = $compagnie;

        return $this;
    }

    public function getPartenaire(): ?string
    {
        return $this->partenaire;
    }

    public function setPartenaire(?string $partenaire): static
    {
        $this->partenaire = $partenaire;

        return $this;
    }

    /**
     * @return Collection<int, Contrat>
     */
    public function getContrats(): Collection
    {
        return $this->contrats;
    }

    public function addContrat(Contrat $contrat): static
    {
        if (!$this->contrats->contains($contrat)) {
            $this->contrats->add($contrat);
            $contrat->setCompagnie($this);
        }

        return $this;
    }

    public function removeContrat(Contrat $contrat): static
    {
        if ($this->contrats->removeElement($contrat)) {
            // set the owning side to null (unless already changed)
            if ($contrat->getCompagnie() === $this) {
                $contrat->setCompagnie(null);
            }
        }

        return $this;
    }
}
