<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Repository\ProduitRepository;
use Symfony\Component\Validator\Constraints as Assert; 


#[ORM\Entity(repositoryClass: ProduitRepository::class)]

class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idProduit;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom ne peut pas être vide")]
    #[Assert\Length(
        min: 6,
        minMessage: "Le nom doit contenir au moins {{ limit }} caractères",
        max: 255,
        maxMessage: "Le nom ne peut pas dépasser {{ limit }} caractères"
    )]
    private ?string $nom= null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "La description ne peut pas être vide")]
    #[Assert\Length(
        min: 6,
        minMessage: "La description doit contenir au moins {{ limit }} caractères",
        max: 255,
        maxMessage: "La description ne peut pas dépasser {{ limit }} caractères"
    )]
    private ?string $description= null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Le prix ne peut pas être vide")]
    #[Assert\PositiveOrZero(message: "Le prix doit être un nombre positif ou nul")]
    private ?int $prix= null;


    #[ORM\Column]
    #[Assert\NotBlank(message: "La quantité ne peut pas être vide")]
    #[Assert\PositiveOrZero(message: "La quantité doit être un nombre positif ou nul")]
    private ?int $quantity= null;

    #[ORM\Column(length: 255)]
    private ?string $image= null;


    public function getIdProduit(): ?int
    {
        return $this->idProduit;
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

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }}