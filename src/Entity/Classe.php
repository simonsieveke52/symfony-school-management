<?php

namespace App\Entity;

use App\Repository\ClasseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ClasseRepository::class)
 */
class Classe
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
     * @ORM\ManyToMany(targetEntity=Prof::class, mappedBy="classes")
     */
    private $profs;

    /**
     * @ORM\OneToMany(targetEntity=Student::class, mappedBy="classe")
     */
    private $students;

    /**
     * @ORM\OneToMany(targetEntity=Anounce::class, mappedBy="classe")
     */
    private $anounces;


    public function __construct()
    {
        $this->profs = new ArrayCollection();
        $this->stdProfiles = new ArrayCollection();
        $this->students = new ArrayCollection();
        $this->anounces = new ArrayCollection();
        
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
    /**
     * @return Collection|Prof[]
     */
    public function getProfs(): Collection
    {
        return $this->profs;
    }

    public function addProf(Prof $prof): self
    {
        if (!$this->profs->contains($prof)) {
            $this->profs[] = $prof;
            $prof->addClass($this);
        }

        return $this;
    }

    public function removeProf(Prof $prof): self
    {
        if ($this->profs->contains($prof)) {
            $this->profs->removeElement($prof);
            $prof->removeClass($this);
        }

        return $this;
    }
    /**
     * @return Collection|Student[]
     */
    public function getStudents(): Collection
    {
        return $this->students;
    }

    public function addStudent(Student $student): self
    {
        if (!$this->students->contains($student)) {
            $this->students[] = $student;
            $student->setClasse($this);
        }

        return $this;
    }

    public function removeStudent(Student $student): self
    {
        if ($this->students->contains($student)) {
            $this->students->removeElement($student);
            // set the owning side to null (unless already changed)
            if ($student->getClasse() === $this) {
                $student->setClasse(null);
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
            $anounce->setClasse($this);
        }

        return $this;
    }

    public function removeAnounce(Anounce $anounce): self
    {
        if ($this->anounces->contains($anounce)) {
            $this->anounces->removeElement($anounce);
            // set the owning side to null (unless already changed)
            if ($anounce->getClasse() === $this) {
                $anounce->setClasse(null);
            }
        }

        return $this;
    }

  


}
