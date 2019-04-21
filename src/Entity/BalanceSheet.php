<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BalanceSheetRepository")
 */
class BalanceSheet
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Movement", mappedBy="balanceSheet", orphanRemoval=true)
     */
    private $movements;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $schoolYear;

    public function __construct()
    {
        $this->movements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Movement[]
     */
    public function getMovements(): Collection
    {
        return $this->movements;
    }

    public function addMovement(Movement $movement): self
    {
        if (!$this->movements->contains($movement)) {
            $this->movements[] = $movement;
            $movement->setBalanceSheet($this);
        }

        return $this;
    }

    public function removeMovement(Movement $movement): self
    {
        if ($this->movements->contains($movement)) {
            $this->movements->removeElement($movement);
            // set the owning side to null (unless already changed)
            if ($movement->getBalanceSheet() === $this) {
                $movement->setBalanceSheet(null);
            }
        }

        return $this;
    }

    public function getSchoolYear(): ?string
    {
        return $this->schoolYear;
    }

    public function setSchoolYear(string $schoolYear): self
    {
        $this->schoolYear = $schoolYear;

        return $this;
    }
}
