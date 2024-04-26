<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserAllergie
 *
 * @ORM\Table(name="user_allergie", indexes={@ORM\Index(name="allergie_id", columns={"allergie_id"})})
 * @ORM\Entity
 */
class UserAllergie
{
    /**
     * @var int
     *
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $userId;

    /**
     * @var int
     *
     * @ORM\Column(name="allergie_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $allergieId;

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function getAllergieId(): ?int
    {
        return $this->allergieId;
    }


}
