<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ProspectRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProspectRepository::class)]
#[ApiResource()]
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

    #[ORM\ManyToOne(inversedBy: 'prospects')]
    private ?User $comrcl = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $relance = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $relanceAt = null;

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

    public function setCreatAt(?\DateTimeInterface $creatAt): static
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
}
