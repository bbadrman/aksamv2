<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use ApiPlatform\Metadata\ApiResource;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource()]
#[ORM\HasLifecycleCallbacks]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_USERNAME', fields: ['username'])]
#[UniqueEntity(fields: ['username'], message: "Ce nom d'utilisateur a déjà été utilisé!")]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(message: "Le nom d'utilisateur est obligatoire")]
    #[Assert\Length(
        min: 4,
        max: 15,
        minMessage: "Le nom d'utilisateur doit contenir au moins quatre caractères",
        maxMessage: "Le nom d'utilisateur doit contenir au maximum quinze caractères"
    )]
    #[Assert\Regex(
        pattern: "/^[a-z0-9_-]{3,15}$/",
        message: "Votre prénom ne doit pas contenir d'espaces, de virgules, de points-virgules ou de deux-points"
    )]
    #[ORM\Column(length: 180)]
    private ?string $username = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column(type: "json")]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;


    #[ORM\Column(length: 255, nullable: true)]
    private ?string $firstname = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lastname = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $embuchAt = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 0, nullable: true)]
    private ?string $remuneration = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $fonction = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private $status = true;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $creatAt = null;

    /**
     * @var Collection<int, Permission>
     */
    #[ORM\ManyToMany(targetEntity: Permission::class, inversedBy: 'users')]
    private Collection $permissions;

    /**
     * @var Collection<int, Product>
     */
    #[ORM\ManyToMany(targetEntity: Product::class, inversedBy: 'users')]
    private Collection $products;

    /**
     * @var Collection<int, Team>
     */
    #[ORM\ManyToMany(targetEntity: Team::class, inversedBy: 'users')]
    private Collection $teams;

    /**
     * @var Collection<int, Contrat>
     */
    #[ORM\OneToMany(targetEntity: Contrat::class, mappedBy: 'user')]
    private Collection $contrats;

    /**
     * @var Collection<int, Prospect>
     */
    #[ORM\OneToMany(targetEntity: Prospect::class, mappedBy: 'comrcl')]
    private Collection $prospects;

    /**
     * @var Collection<int, UserHistory>
     */
    #[ORM\OneToMany(targetEntity: UserHistory::class, mappedBy: 'users')]
    private Collection $userHistories;

    /**
     * @var Collection<int, Prospect>
     */
    #[ORM\OneToMany(targetEntity: Prospect::class, mappedBy: 'autor')]
    private Collection $prospectAutor;

    /**
     * @var Collection<int, Client>
     */
    #[ORM\OneToMany(targetEntity: Client::class, mappedBy: 'cmrcl')]
    private Collection $clients;

    /**
     * @var Collection<int, Transaction>
     */
    #[ORM\OneToMany(targetEntity: Transaction::class, mappedBy: 'comrcl')]
    private Collection $transactions;

    public function __construct()
    {
        $this->permissions = new ArrayCollection();
        $this->products = new ArrayCollection();
        $this->teams = new ArrayCollection();
        $this->contrats = new ArrayCollection();
        $this->prospects = new ArrayCollection();
        $this->userHistories = new ArrayCollection();
        $this->prospectAutor = new ArrayCollection();
        $this->clients = new ArrayCollection();
        $this->transactions = new ArrayCollection();
    }


    #[ORM\PrePersist]
    public function prePersist(): void
    {
        if (empty($this->creatAt)) {
            $timezone = new \DateTimeZone('Europe/Paris'); // Remplacez par le fuseau horaire approprié pour +1 heur
            $this->creatAt = new \Datetime('now', $timezone);
        }
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        //return les role de la table permission se form de chaine caractaire
        $roles = $this->permissions->map(function ($role) {
            return $role->getnom();
        })->toArray();

        $roles[] = 'ROLE_USER';
        // guarantee every user at least has ROLE_USER
        // $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getEmbuchAt(): ?\DateTimeImmutable
    {
        return $this->embuchAt;
    }

    public function setEmbuchAt(?\DateTimeImmutable $embuchAt): static
    {
        $this->embuchAt = $embuchAt;

        return $this;
    }

    public function getRemuneration(): ?string
    {
        return $this->remuneration;
    }

    public function setRemuneration(?string $remuneration): static
    {
        $this->remuneration = $remuneration;

        return $this;
    }

    public function getFonction(): ?string
    {
        return $this->fonction;
    }

    public function setFonction(?string $fonction): static
    {
        $this->fonction = $fonction;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): static
    {
        $this->status = $status;

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

    /**
     * @return Collection<int, Permission>
     */
    public function getPermissions(): Collection
    {
        return $this->permissions;
    }

    public function addPermission(Permission $permission): static
    {
        if (!$this->permissions->contains($permission)) {
            $this->permissions->add($permission);
        }

        return $this;
    }

    public function removePermission(Permission $permission): static
    {
        $this->permissions->removeElement($permission);

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): static
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
        }

        return $this;
    }

    public function removeProduct(Product $product): static
    {
        $this->products->removeElement($product);

        return $this;
    }

    /**
     * @return Collection<int, Team>
     */
    public function getTeams(): Collection
    {
        return $this->teams;
    }

    public function addTeam(Team $team): static
    {
        if (!$this->teams->contains($team)) {
            $this->teams->add($team);
        }

        return $this;
    }

    public function removeTeam(Team $team): static
    {
        $this->teams->removeElement($team);

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
            $contrat->setUser($this);
        }

        return $this;
    }

    public function removeContrat(Contrat $contrat): static
    {
        if ($this->contrats->removeElement($contrat)) {
            // set the owning side to null (unless already changed)
            if ($contrat->getUser() === $this) {
                $contrat->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Prospect>
     */
    public function getProspects(): Collection
    {
        return $this->prospects;
    }

    public function addProspect(Prospect $prospect): static
    {
        if (!$this->prospects->contains($prospect)) {
            $this->prospects->add($prospect);
            $prospect->setComrcl($this);
        }

        return $this;
    }

    public function removeProspect(Prospect $prospect): static
    {
        if ($this->prospects->removeElement($prospect)) {
            // set the owning side to null (unless already changed)
            if ($prospect->getComrcl() === $this) {
                $prospect->setComrcl(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return $this->getUserIdentifier();
    }



    /**
     * @return Collection<int, UserHistory>
     */
    public function getUserHistories(): Collection
    {
        return $this->userHistories;
    }

    public function addUserHistory(UserHistory $userHistory): static
    {
        if (!$this->userHistories->contains($userHistory)) {
            $this->userHistories->add($userHistory);
            $userHistory->setUsers($this);
        }

        return $this;
    }

    public function removeUserHistory(UserHistory $userHistory): static
    {
        if ($this->userHistories->removeElement($userHistory)) {
            // set the owning side to null (unless already changed)
            if ($userHistory->getUsers() === $this) {
                $userHistory->setUsers(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Prospect>
     */
    public function getProspectAutor(): Collection
    {
        return $this->prospectAutor;
    }

    public function addProspectAutor(Prospect $prospectAutor): static
    {
        if (!$this->prospectAutor->contains($prospectAutor)) {
            $this->prospectAutor->add($prospectAutor);
            $prospectAutor->setAutor($this);
        }

        return $this;
    }

    public function removeProspectAutor(Prospect $prospectAutor): static
    {
        if ($this->prospectAutor->removeElement($prospectAutor)) {
            // set the owning side to null (unless already changed)
            if ($prospectAutor->getAutor() === $this) {
                $prospectAutor->setAutor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Client>
     */
    public function getClients(): Collection
    {
        return $this->clients;
    }

    public function addClient(Client $client): static
    {
        if (!$this->clients->contains($client)) {
            $this->clients->add($client);
            $client->setCmrcl($this);
        }

        return $this;
    }

    public function removeClient(Client $client): static
    {
        if ($this->clients->removeElement($client)) {
            // set the owning side to null (unless already changed)
            if ($client->getCmrcl() === $this) {
                $client->setCmrcl(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Transaction>
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    public function addTransaction(Transaction $transaction): static
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions->add($transaction);
            $transaction->setComrcl($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): static
    {
        if ($this->transactions->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getComrcl() === $this) {
                $transaction->setComrcl(null);
            }
        }

        return $this;
    }
}
