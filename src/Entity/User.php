<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping\AttributeOverrides;
use Doctrine\ORM\Mapping\AttributeOverride;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="app_user")
 * 
 * @AttributeOverrides({
 *     @AttributeOverride(name="username",
 *          column=@ORM\Column(
 *              nullable = true,
 *              unique = false
 *          )
 *      ),
 *     @AttributeOverride(name="usernameCanonical",
 *          column=@ORM\Column(
 *              nullable = true,
 *              unique = false
 *          )
 *      ),
 *     @AttributeOverride(name="email",
 *          column=@ORM\Column(
 *              nullable = true
 *          )
 *      ),
 *     @AttributeOverride(name="emailCanonical",
 *          column=@ORM\Column(
 *              name = "email_canonical",
 *              nullable = true,
 *          )
 *      ),
 *     @AttributeOverride(name="password",
 *          column=@ORM\Column(
 *              nullable = true
 *          )
 *      )
 * })
 * @ORM\HasLifecycleCallbacks()
 */
class User extends BaseUser implements EquatableInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank()
     */
    private $phone;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\UFR")
     */
    private $ufr;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Level")
     */
    private $level;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Address")
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $room;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Post")
     */
    private $post;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $membershipFee;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Gender")
     */
    private $gender;

    const NUM_ITEMS = 25;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        if(empty($this->email)) {
            $this->setEmail('user' . substr(md5($phone), 0, 7). '@gmail.com');
        }

        if(empty($this->username)) {
            $this->setUsername($phone);
        }

        return $this;
    }

    public function getUfr(): ?UFR
    {
        return $this->ufr;
    }

    public function setUfr(?UFR $ufr): self
    {
        $this->ufr = $ufr;

        return $this;
    }

    public function getLevel(): ?Level
    {
        return $this->level;
    }

    public function setLevel(?Level $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(?Address $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): self
    {
        $this->post = $post;

        return $this;
    }

        /**
     * {@inheritdoc}
     */
    public function serialize(): string
    {
        return serialize([
            $this->id,
            $this->username,
            $this->phone,
            $this->email,
            $this->password,
            $this->roles,
            $this->ufr,
            $this->level,
            $this->address,
            $this->post,
            $this->room,
            $this->gender,
            $this->membershipFee
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($serialized): void
    {
        [
            $this->id,
            $this->username,
            $this->phoneNumber,
            $this->email,
            $this->password,
            $this->roles,
            $this->ufr,
            $this->level,
            $this->address,
            $this->post,
            $this->room,
            $this->gender,
            $this->membershipFee
        ] = unserialize($serialized);
    }

    public function isEqualTo(UserInterface $user)
    {
        if ($this->password !== $user->getPassword()) {
            return false;
        }
        if ($this->salt !== $user->getSalt()) {
            return false;
        }
        if ($this->username !== $user->getUsername()) {
            return false;
        }
        return true;
    }

    public function getRoom(): ?string
    {
        return $this->room;
    }

    public function setRoom(?string $room): self
    {
        $this->room = $room;

        return $this;
    }

    public function isMembershipFee(): ?bool
    {
        return $this->membershipFee;
    }

    public function setMembershipFee(?bool $membershipFee): self
    {
        $this->membershipFee = $membershipFee;

        return $this;
    }

    public function getGender(): ?Gender
    {
        return $this->gender;
    }

    public function setGender(?Gender $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * @ORM\PrePersist()
     */
    public function defaultRoles()
    {
        $this->roles = ['ROLE_USER'];
    }
}
