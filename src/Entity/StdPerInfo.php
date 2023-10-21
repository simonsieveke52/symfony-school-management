<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use App\Repository\StdPerInfoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StdPerInfoRepository::class)
 */
class StdPerInfo 
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="date")
     * @Assert\LessThan("-15 years")
     */
    private $age;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $cin;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $gendre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $city;

     /**
     * @ORM\Column(type="string", length=255)
     */
    private $phone;

    /**
     * @ORM\OneToOne(targetEntity=Student::class, mappedBy="stdperinfo", cascade={"persist", "remove"})
     */
    private $student;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getAge(): ?\DateTimeInterface
    {
        return $this->age;
    }

    public function setAge(\DateTimeInterface $age): self
    {
        $this->age = $age;

        return $this;
    }

    public function getCin(): ?string
    {
        return $this->cin;
    }

    public function setCin(string $cin): self
    {
        $this->cin = $cin;

        return $this;
    }

    public function getGendre(): ?string
    {
        return $this->gendre;
    }

    public function setGendre(string $gendre): self
    {
        $this->gendre = $gendre;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getStudent(): ?Student
    {
        return $this->student;
    }

    public function setStudent(?Student $student): self
    {
        $this->student = $student;

        // set (or unset) the owning side of the relation if necessary
        $newStdperinfo = null === $student ? null : $this;
        if ($student->getStdperinfo() !== $newStdperinfo) {
            $student->setStdperinfo($newStdperinfo);
        }

        return $this;
    }

}
