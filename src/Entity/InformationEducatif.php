<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * InformationEducatif
 *
 * @ORM\Table(name="information_educatif", indexes={@ORM\Index(name="id_allergie", columns={"id_allergie"})})
 * @ORM\Entity
 */
class InformationEducatif
{
    /**
     * @var int
     *
     * @ORM\Column(name="idInformation", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idinformation;

    /**
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=255, nullable=false)
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="contenu", type="string", length=255, nullable=false)
     */
    private $contenu;

    /**
     * @var string
     *
     * @ORM\Column(name="auteur", type="string", length=255, nullable=false)
     */
    private $auteur;

    /**
     * @var \Allergie
     *
     * @ORM\ManyToOne(targetEntity="Allergie")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_allergie", referencedColumnName="id_al")
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
