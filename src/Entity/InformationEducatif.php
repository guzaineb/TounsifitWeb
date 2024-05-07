<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\InformationEducatifRepository;

#[ORM\Entity(repositoryClass: InformationEducatifRepository::class)]
class InformationEducatif
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]

    private ?int $idinformation=null;

    #[ORM\Column(length: 255)]
    private ?string $titre=null;

   
    #[ORM\Column(length: 255)]
    private ?string  $contenu=null;

    /**
     * @var string
     *
     * @ORM\Column(name="auteur", type="string", length=255, nullable=false)
     */
    private $auteur;

    /**
     * @var \App\Entity\Allergie
     *
     * @ORM\ManyToOne(targetEntity="Allergie")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_allergie", referencedColumnName="id")
     * })
     */
    private $idAllergie;

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

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): static
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getAuteur(): ?string
    {
        return $this->auteur;
    }

    public function setAuteur(string $auteur): static
    {
        $this->auteur = $auteur;

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


}
