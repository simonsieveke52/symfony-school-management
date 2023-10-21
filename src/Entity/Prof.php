<?php

namespace App\Entity;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Repository\ProfRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProfRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class Prof implements UserInterface
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
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $salaire;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $matter;
    
   /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $picture;

    /**
     * @ORM\OneToMany(targetEntity=Course::class, mappedBy="prof", orphanRemoval=true)
     */
    private $courses;

    /**
     * @ORM\OneToMany(targetEntity=Anounce::class, mappedBy="prof")
     */
    private $anounces;

    /**
     * @ORM\OneToMany(targetEntity=Note::class, mappedBy="prof")
     */
    private $notes;
    /**
     * @ORM\ManyToMany(targetEntity=Classe::class, inversedBy="profs")
     */
    private $classes;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $resetToken;

    /**
     * @ORM\OneToMany(targetEntity=Depayement::class, mappedBy="prof")
     */
    private $depayements;

    public function __construct()
    {
        $this->courses = new ArrayCollection();
        $this->anounces = new ArrayCollection();
        $this->notes = new ArrayCollection();
        $this->classes = new ArrayCollection();
        $this->depayements = new ArrayCollection();
    }

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getSalaire(): ?string
    {
        return $this->salaire;
    }

    public function setSalaire(string $salaire): self
    {
        $this->salaire = $salaire;

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

    public function getMatter(): ?string
    {
        return $this->matter;
    }

    public function setMatter(string $matter): self
    {
        $this->matter = $matter;

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
        $roles[] = 'ROLE_PROF';

        return array_unique($roles);
    }

     public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

     public function getPicture(): ?string
     {
         return $this->picture;
     }

     public function setPicture(?string $picture): self
     {
         $this->picture = $picture;

         return $this;
     }

     /**
      * @return Collection|Course[]
      */
     public function getCourses(): Collection
     {
         return $this->courses;
     }

     public function addCourse(Course $course): self
     {
         if (!$this->courses->contains($course)) {
             $this->courses[] = $course;
             $course->setProf($this);
         }

         return $this;
     }

     public function removeCourse(Course $course): self
     {
         if ($this->courses->contains($course)) {
             $this->courses->removeElement($course);
             // set the owning side to null (unless already changed)
             if ($course->getProf() === $this) {
                 $course->setProf(null);
             }
         }

         return $this;
     }

     /**
      * @return Collection|Anounce[]
      */
     public function getAnounces(): Collection
     {
         return $this->anounces;
     }

     public function addAnounce(Anounce $anounce): self
     {
         if (!$this->anounces->contains($anounce)) {
             $this->anounces[] = $anounce;
             $anounce->setProf($this);
         }

         return $this;
     }

     public function removeAnounce(Anounce $anounce): self
     {
         if ($this->anounces->contains($anounce)) {
             $this->anounces->removeElement($anounce);
             // set the owning side to null (unless already changed)
             if ($anounce->getProf() === $this) {
                 $anounce->setProf(null);
             }
         }

         return $this;
     }

     /**
      * @return Collection|Note[]
      */
     public function getNotes(): Collection
     {
         return $this->notes;
     }

     public function addNote(Note $note): self
     {
         if (!$this->notes->contains($note)) {
             $this->notes[] = $note;
             $note->setProf($this);
         }

         return $this;
     }

     public function removeNote(Note $note): self
     {
         if ($this->notes->contains($note)) {
             $this->notes->removeElement($note);
             // set the owning side to null (unless already changed)
             if ($note->getProf() === $this) {
                 $note->setProf(null);
             }
         }

         return $this;
     }
     /**
      * @return Collection|Classe[]
      */
     public function getClasses(): Collection
     {
         return $this->classes;
     }

     public function addClass(Classe $class): self
     {
         if (!$this->classes->contains($class)) {
             $this->classes[] = $class;
         }

         return $this;
     }

     public function removeClass(Classe $class): self
     {
         if ($this->classes->contains($class)) {
             $this->classes->removeElement($class);
         }

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

     /**
      * @return Collection|Depayement[]
      */
     public function getDepayements(): Collection
     {
         return $this->depayements;
     }

     public function addDepayement(Depayement $depayement): self
     {
         if (!$this->depayements->contains($depayement)) {
             $this->depayements[] = $depayement;
             $depayement->setProf($this);
         }

         return $this;
     }

     public function removeDepayement(Depayement $depayement): self
     {
         if ($this->depayements->contains($depayement)) {
             $this->depayements->removeElement($depayement);
             // set the owning side to null (unless already changed)
             if ($depayement->getProf() === $this) {
                 $depayement->setProf(null);
             }
         }

         return $this;
     }

}
