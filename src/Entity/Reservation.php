<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\ReservationRepository;



#[ORM\Entity(repositoryClass:ReservationRepository::class)]

class Reservation
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

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank(message: 'Please enter your email address')]
    #[Assert\Email(message: 'The email address "{{ value }}" is not a valid email.')]
    private ?string $email = null;

    #[ORM\Column(length:255)]
     #[Assert\NotBlank(message: "Le nom ne peut pas être vide")]
     #[Assert\Length(
         min: 6,
         minMessage: "Le nom doit contenir au moins 6 caractères",
         max: 255,
         maxMessage: "Le nom ne peut pas dépasser 255 caractères"
     )]
    private ? string $telephone ;
 

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\GreaterThan('today')]
    private ?\DateTimeInterface $dateReservation = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\GreaterThan('today')]
    private ?\DateTimeInterface $heureReservation = null;
  
    #[ORM\Column]
    #[Assert\Positive]
    private ?int $nombrePersonnes = null;

    
   
   


    #[ORM\ManyToOne(targetEntity: Restaurant::class, inversedBy: 'reservations')]
    private $restaurant;



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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getDateReservation(): ?\DateTimeInterface
    {
        return $this->dateReservation;
    }

    public function setDateReservation(\DateTimeInterface $dateReservation): static
    {
        $this->dateReservation = $dateReservation;

        return $this;
    }

    public function getHeureReservation(): ?\DateTimeInterface
    {
        return $this->heureReservation;
    }

    public function setHeureReservation(\DateTimeInterface $heureReservation): static
    {
        $this->heureReservation = $heureReservation;

        return $this;
    }

    public function getNombrePersonnes(): ?int
    {
        return $this->nombrePersonnes;
    }

    public function setNombrePersonnes(int $nombrePersonnes): static
    {
        $this->nombrePersonnes = $nombrePersonnes;

        return $this;
    }




}
