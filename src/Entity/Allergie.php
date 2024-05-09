<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use App\Repository\AllergieRepository;

use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AllergieRepository::class)]



class Allergie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id=null;

   
     
     #[ORM\Column(length:255)]
     #[Assert\NotBlank(message: "Le nom ne peut pas être vide")]
     #[Assert\Length(
         min: 6,
         minMessage: "Le nom doit contenir au moins 6 caractères",
         max: 255,
         maxMessage: "Le nom ne peut pas dépasser 255 caractères"
     )]
    private ? string $nom ;

   
    #[ORM\Column(length:255)]
    #[Assert\NotBlank(message: "La description ne peut pas être vide")]
    #[Assert\Length(min: 10,  minMessage: "Le nom doit contenir au moins 6 caractères",
        max: 255, maxMessage: "La description ne peut pas dépasser 255 caractères")]
       private ? string $description;

       #[ORM\ManyToMany(targetEntity: "User", mappedBy: "allergies")]
private ?Collection $user;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->user = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(User $user): static
    {
        if (!$this->user->contains($user)) {
            $this->user->add($user);
            $user->addAllergie($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->user->removeElement($user)) {
            $user->removeAllergie($this);
        }

        return $this;
    }
    public function __toString(): string
    {
        return $this->nom ?? ''; // You can change this to whatever property you want to use for the string representation
    }

    

}
