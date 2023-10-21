<?php

namespace App\Entity;

use App\Repository\DepayementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=DepayementRepository::class)
 */
class Depayement
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity=Month::class, inversedBy="depayements")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull
     * @Assert\NotBlank
     */
    private $month;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotNull
     * @Assert\NotBlank
     * @Assert\GreaterThan(value=100)
     */
    private $price;

    /**
     * @ORM\ManyToOne(targetEntity=Prof::class, inversedBy="depayements")
     */
    private $prof;

  
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): void
    {
        $this->price = $price;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getMonth(): ?Month
    {
        return $this->month;
    }

    public function setMonth(?Month $month): self
    {
        $this->month = $month;

        return $this;
    }

    public function getProf(): ?Prof
    {
        return $this->prof;
    }

    public function setProf(?Prof $prof): self
    {
        $this->prof = $prof;

        return $this;
    }
}
