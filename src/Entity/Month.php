<?php

namespace App\Entity;

use App\Repository\MonthRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MonthRepository::class)
 */
class Month
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
    private $outputs;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $inputs;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Depayement::class, mappedBy="month")
     */
    private $depayements;

    /**
     * @ORM\OneToMany(targetEntity=Payement::class, mappedBy="month")
     */
    private $payements;

    public function __construct()
    {
        $this->depayements = new ArrayCollection();
        $this->payements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOutputs(): ?string
    {
        return $this->outputs;
    }

    public function setOutputs(string $outputs): self
    {
        $this->outputs = $outputs;

        return $this;
    }

    public function getInputs(): ?string
    {
        return $this->inputs;
    }

    public function setInputs(string $inputs): self
    {
        $this->inputs = $inputs;

        return $this;
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
            $depayement->setMonth($this);
        }

        return $this;
    }

    public function removeDepayement(Depayement $depayement): self
    {
        if ($this->depayements->contains($depayement)) {
            $this->depayements->removeElement($depayement);
            // set the owning side to null (unless already changed)
            if ($depayement->getMonth() === $this) {
                $depayement->setMonth(null);
            }
        }

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
            $payement->setMonth($this);
        }

        return $this;
    }

    public function removePayement(Payement $payement): self
    {
        if ($this->payements->contains($payement)) {
            $this->payements->removeElement($payement);
            // set the owning side to null (unless already changed)
            if ($payement->getMonth() === $this) {
                $payement->setMonth(null);
            }
        }

        return $this;
    }
}
