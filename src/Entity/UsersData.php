<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UsersDataRepository")
 */
class UsersData
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     * @Assert\File(
     *     maxSize = "25M",
     *     maxSizeMessage = "Veuillez uploader un fichier inferieur a 25MB."
     * )
     */
    private $excel;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExcel(): ?File
    {
        return $this->excel;
    }

    public function setExcel(File $excel): self
    {
        $this->excel = $excel;

        return $this;
    }
}
