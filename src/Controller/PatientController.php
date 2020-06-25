<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\PharmacyRepository;
use App\Entity\Prescription;
use App\Service\PrescriptionCalculator;
use Doctrine\ORM\EntityManagerInterface;
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
     * @Route("/prescription/index/{id}", name="_prescription_index")
     */
    public function index(
        ?UserInterface $user,
        PharmacyRepository $pharmacyRepository,
        PrescriptionCalculator $prescriptionCalcul,
        $id=0
    ) {
        $lat=48.8;
        $lng=2.34;

        $pharmacies=$pharmacyRepository->findClosestPharmacies($lng, $lat);

        if ($id == 0) {
            $prescription = $this->getDoctrine()
                ->getRepository(Prescription::class)
                ->findOneBy(
                    ['user' => $user]
                );
        } else {
            $prescription = $this->getDoctrine()
                ->getRepository(Prescription::class)
                ->findOneBy(
                    ['id' => $id]
                );
        }

        $prices=[];
        $distances=[];
        if (!is_null($prescription)) {
            foreach ($pharmacies as $pharmacy) {
                $prices[$pharmacy->getId()] = $prescriptionCalcul->getTotalAmount($prescription, $pharmacy);
                $distances[$pharmacy->getId()] = $prescriptionCalcul->getDistance($lng, $lat, $pharmacy);
            }
        }

        return $this->render(self::ROLE . '/index.html.twig', [
            'prescription' => $prescription,
            'user' => $user,
            'pharmacies' => $pharmacies,
            'prices' => $prices,
            'distances' => $distances,
        ]);
    }

    /**
     * @Route("/prescription/{prescription}/sendto/{user}", name="_send_to_pharmacist")
     */
    public function sendToPharmacyst(Prescription $prescription, User $user, EntityManagerInterface $entityManager)
    {
        if (in_array("ROLE_PHARMACIST", $user->getRoles())) {
            $prescription->setPharmacist($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('patient_prescription_index');
    }
}
