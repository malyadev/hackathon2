<?php

namespace App\Service;

use App\Entity\Pharmacy;
use App\Entity\Prescription;
use App\Repository\PriceRepository;

class PrescriptionCalculator
{
    private $priceRepository;

    public function __construct(PriceRepository $priceRepository)
    {
        $this->priceRepository = $priceRepository;
    }

    public function getTotalAmount(Prescription $prescription, Pharmacy $pharmacy) : float
    {
        $total=0;
        $prescriptionDrugs=$prescription->getPrescriptionDrugs();
        foreach ($prescriptionDrugs as $prescriptionDrug) {
            $price=$this->priceRepository->findOneBy(
                ['pharmacy' => $pharmacy, 'drug'=>$prescriptionDrug->getDrug()]
            );

            if (!is_null($price)) {
                $total += $price->getPrice();
            }
        }

        return $total;
    }
}
