<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ProspectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Cache;


#[ORM\Entity(repositoryClass: ProspectRepository::class)]
#[Cache(usage: 'READ_ONLY')]
#[ApiResource()]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: "prospect")]

class Prospect
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $gender = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $city = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adress = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $brithAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $source = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $typeProspect = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $raisonSociale = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $codePost = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $gsm = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $assurer = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lastAssure = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $motifResil = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $motifSaise = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $url = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $secdEmail = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $creatAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $activites = null;

    #[ORM\ManyToOne(inversedBy: 'prospects', fetch: 'EAGER')]
    private ?User $comrcl = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $relance = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $relanceAt = null;

    #[ORM\ManyToOne(inversedBy: 'prospects', fetch: 'EAGER')]
    private ?Team $team = null;

    #[ORM\ManyToOne(inversedBy: 'prospects', fetch: 'EAGER')]
    private ?Product $product = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $AffectAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $motifAffect = null;

    /**
     * @var Collection<int, History>
     */

    #[ORM\OneToMany(targetEntity: History::class, mappedBy: 'prospect', cascade: ['remove'])]
    #[Cache(usage: 'NONSTRICT_READ_WRITE')]
    private Collection $histories;

    /**
     * @var Collection<int, RelanceHistory>
     */
    #[ORM\OneToMany(targetEntity: RelanceHistory::class, mappedBy: 'prospect', cascade: ['remove'])]
    #[Cache(usage: 'NONSTRICT_READ_WRITE')]
    private Collection $relanceHistories;

    #[ORM\ManyToOne(inversedBy: 'prospectAutor', fetch: 'EAGER')]
    private ?User $autor = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $comment = null;

    public function __construct()
    {
        $this->histories = new ArrayCollection();
        $this->relanceHistories = new ArrayCollection();
    }

    #[ORM\PrePersist]
    public function prePersist(): void
    {


        if (empty($this->creatAt)) {
            $timezone = new \DateTimeZone('Europe/Paris'); // Remplacez par le fuseau horaire approprié pour +1 heur
            $this->creatAt = new \DateTimeImmutable('now', $timezone);
        }

        // if (empty($this->relanceAt)) {
        //     $timezone = new \DateTimeZone('Europe/Paris');
        //     $this->relanceAt = new \DateTimeImmutable('now', $timezone);
        // }
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(?string $gender): static
    {
        $this->gender = $gender;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(?string $adress): static
    {
        $this->adress = $adress;

        return $this;
    }

    public function getBrithAt(): ?\DateTimeInterface
    {
        return $this->brithAt;
    }

    public function setBrithAt(?\DateTimeInterface $brithAt): static
    {
        $this->brithAt = $brithAt;

        return $this;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(?string $source): static
    {
        $this->source = $source;

        return $this;
    }

    public function getTypeProspect(): ?string
    {
        return $this->typeProspect;
    }

    public function setTypeProspect(?string $typeProspect): static
    {
        $this->typeProspect = $typeProspect;

        return $this;
    }

    public function getRaisonSociale(): ?string
    {
        return $this->raisonSociale;
    }

    public function setRaisonSociale(?string $raisonSociale): static
    {
        $this->raisonSociale = $raisonSociale;

        return $this;
    }

    public function getCodePost(): ?string
    {
        return $this->codePost;
    }

    public function setCodePost(?string $codePost): static
    {
        $this->codePost = $codePost;

        return $this;
    }

    public function getGsm(): ?string
    {
        return $this->gsm;
    }

    public function setGsm(?string $gsm): static
    {
        $this->gsm = $gsm;

        return $this;
    }

    public function getAssurer(): ?string
    {
        return $this->assurer;
    }

    public function setAssurer(?string $assurer): static
    {
        $this->assurer = $assurer;

        return $this;
    }

    public function getLastAssure(): ?string
    {
        return $this->lastAssure;
    }

    public function setLastAssure(?string $lastAssure): static
    {
        $this->lastAssure = $lastAssure;

        return $this;
    }

    public function getMotifResil(): ?string
    {
        return $this->motifResil;
    }

    public function setMotifResil(?string $motifResil): static
    {
        $this->motifResil = $motifResil;

        return $this;
    }

    public function getMotifSaise(): ?string
    {
        return $this->motifSaise;
    }

    public function setMotifSaise(?string $motifSaise): static
    {
        $this->motifSaise = $motifSaise;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function getSecdEmail(): ?string
    {
        return $this->secdEmail;
    }

    public function setSecdEmail(?string $secdEmail): static
    {
        $this->secdEmail = $secdEmail;

        return $this;
    }



    public function getCreatAt(): ?\DateTimeInterface
    {
        return $this->creatAt;
    }

    public function setCreatAt(?\DateTimeInterface  $creatAt): static
    {
        $this->creatAt = $creatAt;

        return $this;
    }

    public function getActivites(): ?string
    {
        return $this->activites;
    }

    public function setActivites(?string $activites): static
    {
        $this->activites = $activites;

        return $this;
    }

    public function getComrcl(): ?User
    {
        return $this->comrcl;
    }

    public function setComrcl(?User $comrcl): static
    {
        $this->comrcl = $comrcl;

        return $this;
    }

    public function getRelance(): ?string
    {
        return $this->relance;
    }

    public function setRelance(?string $relance): static
    {
        $this->relance = $relance;

        return $this;
    }

    public function getRelanceAt(): ?\DateTimeImmutable
    {
        return $this->relanceAt;
    }

    public function setRelanceAt(?\DateTimeImmutable $relanceAt): static
    {
        $this->relanceAt = $relanceAt;

        return $this;
    }

    public function getTeam(): ?Team
    {
        return $this->team;
    }

    public function setTeam(?Team $team): static
    {
        $this->team = $team;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): static
    {
        $this->product = $product;

        return $this;
    }

    public function getAffectAt(): ?\DateTimeImmutable
    {
        return $this->AffectAt;
    }

    public function setAffectAt(?\DateTimeImmutable $AffectAt): static
    {
        $this->AffectAt = $AffectAt;

        return $this;
    }

    public function getMotifAffect(): ?string
    {
        return $this->motifAffect;
    }

    public function setMotifAffect(?string $motifAffect): static
    {
        $this->motifAffect = $motifAffect;

        return $this;
    }

    /**
     * @return Collection<int, History>
     */
    public function getHistories(): Collection
    {
        return $this->histories;
    }

    public function addHistory(History $history): static
    {
        if (!$this->histories->contains($history)) {
            $this->histories->add($history);
            $history->setProspect($this);
        }

        return $this;
    }

    public function removeHistory(History $history): static
    {
        if ($this->histories->removeElement($history)) {
            // set the owning side to null (unless already changed)
            if ($history->getProspect() === $this) {
                $history->setProspect(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, RelanceHistory>
     */
    public function getRelanceHistories(): Collection
    {
        return $this->relanceHistories;
    }

    public function addRelanceHistory(RelanceHistory $relanceHistory): static
    {
        if (!$this->relanceHistories->contains($relanceHistory)) {
            $this->relanceHistories->add($relanceHistory);
            $relanceHistory->setProspect($this);
        }

        return $this;
    }

    public function removeRelanceHistory(RelanceHistory $relanceHistory): static
    {
        if ($this->relanceHistories->removeElement($relanceHistory)) {
            // set the owning side to null (unless already changed)
            if ($relanceHistory->getProspect() === $this) {
                $relanceHistory->setProspect(null);
            }
        }

        return $this;
    }

    public function getAutor(): ?User
    {
        return $this->autor;
    }

    public function setAutor(?User $autor): static
    {
        $this->autor = $autor;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): static
    {
        $this->comment = $comment;

        return $this;
    }
}
