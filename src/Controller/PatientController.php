<?php

namespace App\Controller;

use App\Repository\PharmacyRepository;
use App\Entity\Prescription;
use App\Entity\PrescriptionDrug;
use App\Service\PrescriptionCalculator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/patient", name="patient")
 */
class PatientController extends AbstractController
{
    const ROLE = 'patient';

    /**
     * @Route("/prescription/index", name="_prescription_index")
     */
    public function index(
        ?UserInterface $user,
        PharmacyRepository $pharmacyRepository,
        PrescriptionCalculator $prescriptionCalcul
    ) {
        $lat=48.8;
        $lng=2.34;

        $pharmacies=$pharmacyRepository->findClosestPharmacies($lng, $lat);

        $prescription = $this->getDoctrine()
            ->getRepository(Prescription::class)
            ->findOneBy(
                ['user' => $user]
            );

        $prices=[];
        $distances=[];
        $prescriptionDrugs = [];
        if (!is_null($prescription)) {
            $prescriptionDrugs = $this->getDoctrine()
                ->getRepository(PrescriptionDrug::class)
                ->findBy(
                    ['prescription' => $prescription]
                );

            foreach ($pharmacies as $pharmacy) {
                $prices[$pharmacy->getId()] = $prescriptionCalcul->getTotalAmount($prescription, $pharmacy);
                $distances[$pharmacy->getId()] = $prescriptionCalcul->getDistance($lng, $lat, $pharmacy);
            }
        }

        return $this->render(self::ROLE . '/index.html.twig', [
            'prescriptionDrugs' => $prescriptionDrugs,
            'user' => $user,
            'pharmacies' => $pharmacies,
            'prices' => $prices,
            'distances' => $distances,
        ]);
    }
}
