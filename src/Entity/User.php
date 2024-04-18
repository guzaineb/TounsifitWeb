<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

use App\Repository\UserRepository;

#[ORM\Entity(repositoryClass: UserRepository::class)]

class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var string|null
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=true)
     */
    private $nom;

    /**
     * @var string|null
     *
     * @ORM\Column(name="prenom", type="string", length=255, nullable=true)
     */
    private $prenom;

    /**
     * @var string|null
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=true)
     */
    private $password;

    /**
     * @var string|null
     *
     * @ORM\Column(name="roles", type="text", length=0, nullable=true)
     */
    private $roles;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="isconnected", type="boolean", nullable=true)
     */
    private $isconnected;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="isbanned", type="boolean", nullable=true)
     */
    private $isbanned = '0';

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Allergie", inversedBy="user")
     * @ORM\JoinTable(name="user_allergie",
     *   joinColumns={
     *     @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="allergie_id", referencedColumnName="id")
     *   }
     * )
     */
    
     #[ORM\ManyToMany(targetEntity: "Allergie", inversedBy: "users")]
     #[ORM\JoinTable(name: "user_allergie")]
    private $allergie = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->allergie = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles(): ?string
    {
        return $this->roles;
    }

    public function setRoles(?string $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function isIsconnected(): ?bool
    {
        return $this->isconnected;
    }

    public function setIsconnected(?bool $isconnected): static
    {
        $this->isconnected = $isconnected;

        return $this;
    }

    public function isIsbanned(): ?bool
    {
        return $this->isbanned;
    }

    public function setIsbanned(?bool $isbanned): static
    {
        $this->isbanned = $isbanned;

        return $this;
    }

    /**
     * @return Collection<int, Allergie>
     */
    public function getAllergie(): Collection
    {
        return $this->allergie;
    }

    public function addAllergie(Allergie $allergie): static
    {
        if (!$this->allergie->contains($allergie)) {
            $this->allergie->add($allergie);
        }

        return $this;
    }

    public function removeAllergie(Allergie $allergie): static
    {
        $this->allergie->removeElement($allergie);

        return $this;
    }

}
