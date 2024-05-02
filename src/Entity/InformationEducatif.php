<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\InformationEducatifRepository;

#[ORM\Entity(repositoryClass:InformationEducatifRepository::class)]
class InformationEducatif
{  
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idinformation = null;

    #[ORM\Column(type: "string", length: 255)]
    #[Assert\NotBlank(message: "Le titre ne peut pas être vide")]
    #[Assert\Length(
        min: 6,
        minMessage: "Le titre doit contenir au moins {{ min }} caractères",
        max: 255,
        maxMessage: "Le titre ne peut pas dépasser {{ max }} caractères"
    )]
    private ?string $titre = null;

    #[ORM\Column(type: "string", length: 255)]
    #[Assert\NotBlank(message: "Le symptome ne peut pas être vide")]
    #[Assert\Length(
        min: 4,
        minMessage: "Le symptome doit contenir au moins {{ min }} caractères",
        max: 255,
        maxMessage: "Le symptome ne peut pas dépasser {{ lmax }} caractères"
    )]

    private ?string $symptome = null;
    #[ORM\Column(type: "text")]
    #[Assert\NotBlank(message: "Les causes  ne peut pas être vide")]

   
    private ?string $causes = null;


    #[ORM\Column(type: "string", length: 255)]
    #[Assert\NotBlank(message: "Le traitement ne peut pas être vide")]

    private ?string $traitement = null;
    #[ORM\Column(type: "string", length: 255)]
   

    private ?string $image = null;

    #[ORM\ManyToOne(targetEntity: Allergie::class)]
    #[ORM\JoinColumn(name: 'id_allergie')]
    #[Assert\NotBlank(message: "séléctionner une allergie ")]

    private ?Allergie $idAllergie = null;
    #[ORM\Column(type: "integer", options: ["default" => 0])]
    private int $likes = 0;
    
    #[ORM\Column(type: "integer", options: ["default" => 0])]
    private int $dislikes = 0;


    public function getIdinformation(): ?int
    {
        return $this->idinformation;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getSymptome(): ?string
    {
        return $this->symptome;
    }

    public function setSymptome(string $symptome): static
    {
        $this->symptome = $symptome;

        return $this;
    }

    public function getCauses(): ?string
    {
        return $this->causes;
    }

    public function setCauses(string $causes): static
    {
        $this->causes = $causes;

        return $this;
    }

    public function getIdAllergie(): ?Allergie
    {
        return $this->idAllergie;
    }

    public function setIdAllergie(?Allergie $idAllergie): static
    {
        $this->idAllergie = $idAllergie;

        return $this;
    }

    public function getTraitement(): ?string
    {
        return $this->traitement;
    }

    public function setTraitement(string $traitement): static
    {
        $this->traitement = $traitement;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }
   
    
    // Ajoutez les getters et setters pour les nouveaux champs
    public function getLikes(): int
    {
        return $this->likes;
    }
    
    public function setLikes(int $likes): void
    {
        $this->likes = $likes;
    }
    
    public function getDislikes(): int
    {
        return $this->dislikes;
    }
    
    public function setDislikes(int $dislikes): void
    {
        $this->dislikes = $dislikes;
    }


}
