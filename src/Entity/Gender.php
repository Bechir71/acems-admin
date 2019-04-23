<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GenderRepository")
 */
class Gender
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
    private $value;

    const MALE = 'gender.male';
    const FEMALE = 'gender.female';
    const GENDERS = [
        self::MALE,
        self::FEMALE
    ];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function __toString()
    {
        return $this->value;
    }

    /**
     * Twig getters
     */
    public function __call($name, $arguments)
    {
        $name = 'gender.' . strtolower($name);
        
        foreach (self::GENDERS as $gender) {
            if($gender == $name)
                return $gender;
        }
    }
}
