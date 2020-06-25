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

    public function getDistance(float $lng, float $lat, Pharmacy $pharmacy) : float
    {
                $distance =  6378 * acos(cos(deg2rad($lat))
                * cos(deg2rad($pharmacy->getLatitude()??0))
                * cos(deg2rad($pharmacy->getLongitude()??0)
                - deg2rad($lng))
                + sin(deg2rad($lat))
                * sin(deg2rad($pharmacy->getLatitude()??0)));

        return round($distance, 1);
    }
}
