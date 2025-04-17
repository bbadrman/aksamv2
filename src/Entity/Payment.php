<?php

namespace App\Entity;

use App\Repository\PaymentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaymentRepository::class)]
class Payment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $frais = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $cotisation = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $creatAt = null;

    #[ORM\ManyToOne(inversedBy: 'payments')]
    private ?Contrat $contrat = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $contrePartie = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $type = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $transaction = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $moyen = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $montant = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateRegelement = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $montant1 = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $montant2 = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $montant3 = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $montant4 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $transaction1 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $transaction2 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $transaction3 = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $datePayment = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $datePayment1 = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $datePayment3 = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $datePayment2 = null;

    #[ORM\Column(nullable: true)]
    private ?int $nmbrReglement = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFrais(): ?string
    {
        return $this->frais;
    }

    public function setFrais(?string $frais): static
    {
        $this->frais = $frais;

        return $this;
    }

    public function getCotisation(): ?string
    {
        return $this->cotisation;
    }

    public function setCotisation(?string $cotisation): static
    {
        $this->cotisation = $cotisation;

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

    public function getContrat(): ?Contrat
    {
        return $this->contrat;
    }

    public function setContrat(?Contrat $contrat): static
    {
        $this->contrat = $contrat;

        return $this;
    }

    public function getContrePartie(): ?string
    {
        return $this->contrePartie;
    }

    public function setContrePartie(?string $contrePartie): static
    {
        $this->contrePartie = $contrePartie;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getTransaction(): ?string
    {
        return $this->transaction;
    }

    public function setTransaction(?string $transaction): static
    {
        $this->transaction = $transaction;

        return $this;
    }

    public function getMoyen(): ?string
    {
        return $this->moyen;
    }

    public function setMoyen(?string $moyen): static
    {
        $this->moyen = $moyen;

        return $this;
    }

    public function getMontant(): ?string
    {
        return $this->montant;
    }

    public function setMontant(?string $montant): static
    {
        $this->montant = $montant;

        return $this;
    }

    public function getDateRegelement(): ?\DateTimeInterface
    {
        return $this->dateRegelement;
    }

    public function setDateRegelement(?\DateTimeInterface $dateRegelement): static
    {
        $this->dateRegelement = $dateRegelement;

        return $this;
    }

    public function getMontant1(): ?string
    {
        return $this->montant1;
    }

    public function setMontant1(?string $montant1): static
    {
        $this->montant1 = $montant1;

        return $this;
    }

    public function getMontant2(): ?string
    {
        return $this->montant2;
    }

    public function setMontant2(?string $montant2): static
    {
        $this->montant2 = $montant2;

        return $this;
    }

    public function getMontant3(): ?string
    {
        return $this->montant3;
    }

    public function setMontant3(?string $montant3): static
    {
        $this->montant3 = $montant3;

        return $this;
    }

    public function getMontant4(): ?string
    {
        return $this->montant4;
    }

    public function setMontant4(?string $montant4): static
    {
        $this->montant4 = $montant4;

        return $this;
    }

    public function getTransaction1(): ?string
    {
        return $this->transaction1;
    }

    public function setTransaction1(?string $transaction1): static
    {
        $this->transaction1 = $transaction1;

        return $this;
    }

    public function getTransaction2(): ?string
    {
        return $this->transaction2;
    }

    public function setTransaction2(?string $transaction2): static
    {
        $this->transaction2 = $transaction2;

        return $this;
    }

    public function getTransaction3(): ?string
    {
        return $this->transaction3;
    }

    public function setTransaction3(?string $transaction3): static
    {
        $this->transaction3 = $transaction3;

        return $this;
    }

    public function getDatePayment(): ?\DateTimeInterface
    {
        return $this->datePayment;
    }

    public function setDatePayment(?\DateTimeInterface $datePayment): static
    {
        $this->datePayment = $datePayment;

        return $this;
    }

    public function getDatePayment1(): ?\DateTimeInterface
    {
        return $this->datePayment1;
    }

    public function setDatePayment1(?\DateTimeInterface $datePayment1): static
    {
        $this->datePayment1 = $datePayment1;

        return $this;
    }



    public function getDatePayment3(): ?\DateTimeInterface
    {
        return $this->datePayment3;
    }

    public function setDatePayment3(?\DateTimeInterface $datePayment3): static
    {
        $this->datePayment3 = $datePayment3;

        return $this;
    }

    public function getDatePayment2(): ?\DateTimeInterface
    {
        return $this->datePayment2;
    }

    public function setDatePayment2(?\DateTimeInterface $datePayment2): static
    {
        $this->datePayment2 = $datePayment2;

        return $this;
    }

    public function getNmbrReglement(): ?int
    {
        return $this->nmbrReglement;
    }

    public function setNmbrReglement(?int $nmbrReglement): static
    {
        $this->nmbrReglement = $nmbrReglement;

        return $this;
    }
}
