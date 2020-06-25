<?php

namespace App\Entity;

use App\Repository\DrugRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DrugRepository::class)
 */
class Drug
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
     * @ORM\Column(type="integer")
     */
    private $frequency;

    /**
     * @ORM\Column(type="integer")
     */
    private $dose;

    /**
     * @ORM\Column(type="integer")
     */
    private $duration;

    /**
     * @ORM\OneToMany(targetEntity=PrescriptionDrug::class, mappedBy="drug", orphanRemoval=true)
     */
    private $prescriptionDrugs;

    /**
     * @ORM\OneToMany(targetEntity=Price::class, mappedBy="drug", orphanRemoval=true)
     */
    private $prices;

    public function __construct()
    {
        $this->prescriptionDrugs = new ArrayCollection();
        $this->prices = new ArrayCollection();
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

    public function getFrequency(): ?int
    {
        return $this->frequency;
    }

    public function setFrequency(int $frequency): self
    {
        $this->frequency = $frequency;

        return $this;
    }

    public function getDose(): ?int
    {
        return $this->dose;
    }

    public function setDose(int $dose): self
    {
        $this->dose = $dose;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * @return Collection|PrescriptionDrug[]
     */
    public function getPrescriptionDrugs(): Collection
    {
        return $this->prescriptionDrugs;
    }

    public function addPrescriptionDrug(PrescriptionDrug $prescriptionDrug): self
    {
        if (!$this->prescriptionDrugs->contains($prescriptionDrug)) {
            $this->prescriptionDrugs[] = $prescriptionDrug;
            $prescriptionDrug->setDrug($this);
        }

        return $this;
    }

    public function removePrescriptionDrug(PrescriptionDrug $prescriptionDrug): self
    {
        if ($this->prescriptionDrugs->contains($prescriptionDrug)) {
            $this->prescriptionDrugs->removeElement($prescriptionDrug);
            // set the owning side to null (unless already changed)
            if ($prescriptionDrug->getDrug() === $this) {
                $prescriptionDrug->setDrug(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Price[]
     */
    public function getPrices(): Collection
    {
        return $this->prices;
    }

    public function addPrice(Price $price): self
    {
        if (!$this->prices->contains($price)) {
            $this->prices[] = $price;
            $price->setDrug($this);
        }

        return $this;
    }

    public function removePrice(Price $price): self
    {
        if ($this->prices->contains($price)) {
            $this->prices->removeElement($price);
            // set the owning side to null (unless already changed)
            if ($price->getDrug() === $this) {
                $price->setDrug(null);
            }
        }

        return $this;
    }
}
