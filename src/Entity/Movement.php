<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MovementRepository")
 */
class Movement
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="integer")
     */
    private $amount;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\BalanceSheet", inversedBy="movements")
     * @ORM\JoinColumn(nullable=false)
     */
    private $balanceSheet;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    const INPUT = 'movement.type.input';
    const OUTPUT = 'movement.type.output';
    const TYPES = [
        slef::INPUT,
        self::OUTPUT
    ];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getBalanceSheet(): ?BalanceSheet
    {
        return $this->balanceSheet;
    }

    public function setBalanceSheet(?BalanceSheet $balanceSheet): self
    {
        $this->balanceSheet = $balanceSheet;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        if(!\in_array($type, self::TYPES)) {
            throw new \Exception("$type is not in the types");
        }

        $this->type = $type;

        return $this;
    }

    public function __call($name, $arguments)
    {
        $name = 'movement.type.output' . strtolower($name);
        foreach (self::TYPES as $type) {
            if($type == $name)
                return $type;
        }
    }
}
