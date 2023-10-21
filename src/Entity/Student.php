<?php

namespace App\Entity;

use App\Repository\StudentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StudentRepository::class)
 */
class Student
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=StdCv::class, inversedBy="student", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $stdcv;

    /**
     * @ORM\OneToOne(targetEntity=StdChoice::class, inversedBy="student", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $stdchoice;

    /**
     * @ORM\OneToOne(targetEntity=StdProfile::class, inversedBy="student", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $profile;

    /**
     * @ORM\OneToOne(targetEntity=StdPerInfo::class, inversedBy="student", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    public $stdperinfo;


    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="student", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    public $user;



    /**
     * @ORM\ManyToMany(targetEntity=Note::class, mappedBy="students")
     */
    private $notes;

    /**
     * @ORM\ManyToOne(targetEntity=Classe::class, inversedBy="students")
     */
    private $classe;

    /**
     * @ORM\OneToMany(targetEntity=Payement::class, mappedBy="student")
     */
    private $payements;


    public function __construct()
    {
        $this->notes = new ArrayCollection();
        $this->payements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStdcv(): ?StdCv
    {
        return $this->stdcv;
    }

    public function setStdcv(StdCv $stdcv): void
    {
        $this->stdcv = $stdcv;
    }

    public function getStdchoice(): ?StdChoice
    {
        return $this->stdchoice;
    }

    public function setStdchoice(StdChoice $stdchoice): void
    {
        $this->stdchoice = $stdchoice;
    }

    public function getProfile(): ?StdProfile
    {
        return $this->profile;
    }

    public function setProfile(StdProfile $profile): void
    {
        $this->profile = $profile;
    }

    public function getStdperinfo(): ?StdPerInfo
    {
        return $this->stdperinfo;
    }

    public function setStdperinfo(StdPerInfo $stdperinfo): void
    {
        $this->stdperinfo = $stdperinfo;
    }



    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

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
            $note->addStudent($this);
        }

        return $this;
    }

    public function removeNote(Note $note): self
    {
        if ($this->notes->contains($note)) {
            $this->notes->removeElement($note);
            $note->removeStudent($this);
        }

        return $this;
    }

    public function getClasse(): ?Classe
    {
        return $this->classe;
    }

    public function setClasse(?Classe $classe): self
    {
        $this->classe = $classe;

        return $this;
    }

    /**
     * @return Collection|Payement[]
     */
    public function getPayements(): Collection
    {
        return $this->payements;
    }

    public function addPayement(Payement $payement): self
    {
        if (!$this->payements->contains($payement)) {
            $this->payements[] = $payement;
            $payement->setStudent($this);
        }

        return $this;
    }

    public function removePayement(Payement $payement): self
    {
        if ($this->payements->contains($payement)) {
            $this->payements->removeElement($payement);
            // set the owning side to null (unless already changed)
            if ($payement->getStudent() === $this) {
                $payement->setStudent(null);
            }
        }

        return $this;
    }
}
