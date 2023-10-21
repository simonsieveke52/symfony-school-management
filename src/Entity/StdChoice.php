<?php

namespace App\Entity;

use App\Repository\StdChoiceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StdChoiceRepository::class)
 */
class StdChoice
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
    private $bactype;

    /**
     * @ORM\OneToOne(targetEntity=Student::class, mappedBy="stdchoice", cascade={"persist", "remove"})
     */
    private $student;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBactype(): ?string
    {
        return $this->bactype;
    }

    public function setBactype(string $bactype): self
    {
        $this->bactype = $bactype;

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
        if ($student->getStdchoice() !== $this) {
            $student->setStdchoice($this);
        }

        return $this;
    }
}
