<?php

namespace App\Entity;

use App\Repository\EtatRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Etat
 *
 * @ORM\Entity(repositoryClass="App\Repository\EtatRepository")
 * @ORM\Table(name="etat")
 */
class Etat
{
    /**
     * Etats
     *
     * 0 Archived
     * 1 Open
     * 2 Draft
     * 3 Closed
     * 4 Active
     * 5 Finished
     * 6 Canceled
     */
    static $etats;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=30)
     */
    private $libelle;

    public function __construct(EtatRepository $etatRepository)
    {
        if (self::$etats == null) {
            self::$etats = $etatRepository->findAll();
        }
    }

    public static function isOpen($id): bool
    {
        return $id == self::$etats[0]->getId();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public static function isDraft($id): bool
    {
        return $id == self::$etats[1]->getId();
    }

    public static function isClosed($id): bool
    {
        return $id == self::$etats[2]->getId();
    }

    public static function isActive($id): bool
    {
        return $id == self::$etats[3]->getId();
    }

    public static function isFinished($id): bool
    {
        return $id == self::$etats[4]->getId();
    }

    public static function isCanceled($id): bool
    {
        return $id == self::$etats[5]->getId();
    }

    public static function isArchived($id): bool
    {
        return $id == self::$etats[6]->getId();
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getOpen()
    {
        return self::$etats[0];
    }

    public function getDraft()
    {
        return self::$etats[1];
    }

    public function getClosed()
    {
        return self::$etats[2];
    }

    public function getActive()
    {
        return self::$etats[3];
    }

    public function getFinished()
    {
        return self::$etats[4];
    }

    public function getCanceled()
    {
        return self::$etats[5];
    }

    public function getArchived()
    {
        return self::$etats[6];
    }
}
