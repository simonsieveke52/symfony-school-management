<?php

namespace App\Entity;

use App\Repository\StdProfileRepository;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StdProfileRepository::class)
 
 */
 
class StdProfile 
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
    private $state;

    /**
     * @ORM\Column(type="integer")
     */
    private $note;

    /**
     * @ORM\OneToOne(targetEntity=Student::class, mappedBy="profile", cascade={"persist", "remove"})
     */
    private $student;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(int $note): self
    {
        $this->note = $note;

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
        if ($student->getProfile() !== $this) {
            $student->setProfile($this);
        }

        return $this;
    }

   
   

    
}
