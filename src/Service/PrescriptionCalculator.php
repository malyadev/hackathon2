<?php

namespace App\Service;

use App\Entity\Pharmacy;
use App\Entity\Prescription;
use App\Repository\PriceRepository;
use Symfony\Component\HttpClient\HttpClient;

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

    public function getCoordinates(string $zipcode) : array
    {
        $coordinates=[
            'longitude' => 2.34,
            'latitude' => 48.8
        ];

        $client = HttpClient::create();
        $response = $client->request(
            'GET',
            'https://api-adresse.data.gouv.fr/search/?q=8+bd+du+port&postcode='.$zipcode
        );

        $statusCode = $response->getStatusCode();

        if ($statusCode === 200) {
            $content = $response->getContent();
            // get the response in JSON format

            $content = $response->toArray();
            // convert the response (here in JSON) to an PHP array

            $extracted=$content['features'][0]['geometry']['coordinates'];
            $coordinates['longitude']=$extracted['0'];
            $coordinates['latitude']=$extracted['1'];
        }

        return $coordinates;
    }
}
