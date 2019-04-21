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
     * @ORM\Column(type="integer")
     */
    private $fund;

    /**
     * @ORM\Column(type="integer")
     */
    private $donations;

    /**
     * @ORM\Column(type="integer")
     */
    private $outputs;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $schoolYear;

    const COTISATION = 3000;

    public function __construct()
    {
        $this->fund = 0;
        $this->outputs = 0;
        $this->donations = 0;
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
            
            $amount = $movement->getAmount();

            if($movement->getType() == Movement::INPUT) {
                $this->plus($amount);
                $this->donations += $amount;
            } else {
                $this->plus(-$amount);
            }
        }

        return $this;
    }

    public function removeMovement(Movement $movement): self
    {
        if ($this->movements->contains($movement)) {
            $this->movements->removeElement($movement);
            $this->plus(-$movement->getAmount());
            // set the owning side to null (unless already changed)
            if ($movement->getBalanceSheet() === $this) {
                $movement->setBalanceSheet(null);
            }
        }

        return $this;
    }

    public function getFund(): ?int
    {
        return $this->fund;
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

    public function getDonations(): ?int
    {
        return $this->donations;
    }

    public function getOutputs(): ?int
    {
        return $this->outputs;
    }

    public function plus($amount)
    {
        $this->fund += $amount;

        if($amount < 0) {
            $this->outputs -= $amount;
        }
    }
}
