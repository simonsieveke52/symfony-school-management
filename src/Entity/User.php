<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    public $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $picture;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

         /**
     * @ORM\Column(type="json")
     */
    private $roles=[];

    /**
     * @ORM\OneToOne(targetEntity=Student::class, mappedBy="user", cascade={"persist", "remove"})
     */
    public $student;

    /**
     * @ORM\Column(type="string", length=55, nullable=true)
     */
    private $activationToken;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $resetToken;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }


     /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

   /**
     * @see UserInterface
     */
     public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

     public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

     public function getStudent(): ?Student
     {
         return $this->student;
     }

     public function setStudent(Student $student): self
     {
         $this->student = $student;

         // set the owning side of the relation if necessary
         if ($student->getUser() !== $this) {
             $student->setUser($this);
         }

         return $this;
     }

     public function getActivationToken(): ?string
     {
         return $this->activationToken;
     }

     public function setActivationToken(?string $activationToken): self
     {
         $this->activationToken = $activationToken;

         return $this;
     }

     public function getResetToken(): ?string
     {
         return $this->resetToken;
     }

     public function setResetToken(?string $resetToken): self
     {
         $this->resetToken = $resetToken;

         return $this;
     }
}
