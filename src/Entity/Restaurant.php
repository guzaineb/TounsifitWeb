<?php

namespace App\Entity;

use App\Repository\RestaurantRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass:RestaurantRepository::class)]

class Restaurant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private  $nom;

    #[ORM\Column(type: "string", length: 255)]
    #[Assert\NotBlank(message: "Le adresse ne peut pas être vide")]
    #[Assert\Length(
        min: 6,
        minMessage: "Le adresse doit contenir au moins {{ min }} caractères",
        max: 255,
        maxMessage: "Le adresse ne peut pas dépasser {{ max }} caractères"
    )]
    private ?string $adresse = null;
    #[ORM\Column(length:255)]    
   private ? string $img ;

    #[ORM\Column(length:255)]
    #[Assert\NotBlank(message: "Le type ne peut pas être vide")]
    
   private ? string $type ;

  

    #[ORM\OneToMany(targetEntity: Reservation::class, mappedBy: 'restaurant')]
    private Collection $reservations;

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

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getImg(): ?string
    {
        return $this->img;
    }

    public function setImg(string $img): static
    {
        $this->img = $img;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }


}
