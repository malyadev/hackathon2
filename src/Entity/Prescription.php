<?php

namespace App\Entity;

use App\Repository\PrescriptionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PrescriptionRepository::class)
 */
class Prescription
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="prescriptions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=PrescriptionDrug::class, mappedBy="prescription", orphanRemoval=true)
     *
     * @Assert\Valid
     */
    private $prescriptionDrugs;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private $practitioner;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private $pharmacist;

    public function __construct()
    {
        $this->prescriptionDrugs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

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
            $prescriptionDrug->setPrescription($this);
        }

        return $this;
    }

    public function removePrescriptionDrug(PrescriptionDrug $prescriptionDrug): self
    {
        if ($this->prescriptionDrugs->contains($prescriptionDrug)) {
            $this->prescriptionDrugs->removeElement($prescriptionDrug);
            // set the owning side to null (unless already changed)
            if ($prescriptionDrug->getPrescription() === $this) {
                $prescriptionDrug->setPrescription(null);
            }
        }

        return $this;
    }

    public function getPractitioner(): ?User
    {
        return $this->practitioner;
    }

    public function setPractitioner(?User $practitioner): self
    {
        $this->practitioner = $practitioner;

        return $this;
    }

    public function getPharmacist(): ?User
    {
        return $this->pharmacist;
    }

    public function setPharmacist(?User $pharmacist): self
    {
        $this->pharmacist = $pharmacist;

        return $this;
    }
}
