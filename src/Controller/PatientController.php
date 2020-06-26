<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\PatientRepository;
use App\Repository\PharmacyRepository;
use App\Entity\Prescription;
use App\Service\PrescriptionCalculator;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/patient", name="patient")
 */
class PatientController extends AbstractController
{
    const ROLE = 'patient';

    /**
     * @Route("/prescription/index/{id}/{code}", name="_prescription_index")
     */
    public function index(
        ?UserInterface $user,
        PharmacyRepository $pharmacyRepository,
        PatientRepository $patientRepository,
        PrescriptionCalculator $prescriptionCalcul,
        Request $request,
        $id = 0,
        $code = 0
    ) {

        if ($id == 0) {
            $prescription = $this->getDoctrine()
                ->getRepository(Prescription::class)
                ->findOneBy(
                    ['user' => $user]
                );

            if (!is_null($prescription)) {
                return $this->redirectToRoute('patient_prescription_index', ['id' => $prescription->getId()]);
            }
        }

        $prescription = $this->getDoctrine()
            ->getRepository(Prescription::class)
            ->findOneBy(
                ['id' => $id]
            );

        if (!is_null($prescription)) {
            $this->denyAccessUnlessGranted('read', $prescription);
        }

        $prefered=$patientRepository->findOneBy(['patient'=>$user])->getPharmacy();

        $pharmacies=[];
        $prices=[];
        $distances=[];
        $zipcode="45000";
        if ($code == 1) {
            $zipcode = $request->get('postcode');
            if (is_null($zipcode)) {
                $zipcode="45000";
            }
            $zipcode=trim($zipcode);
            if (!preg_match("/^(([0-8][0-9])|(9[0-5])|(2[ab]))[0-9]{3}$/", $zipcode)) {
                $zipcode="45000";
            }

            $coordinates=$prescriptionCalcul->getCoordinates($zipcode);
            $lat=$coordinates['latitude'];
            $lng=$coordinates['longitude'];

            $pharmacies=$pharmacyRepository->findClosestPharmacies($lng, $lat);

            if (!is_null($prescription)) {
                foreach ($pharmacies as $pharmacy) {
                    $prices[$pharmacy->getId()] = $prescriptionCalcul->getTotalAmount($prescription, $pharmacy);
                    $distances[$pharmacy->getId()] = $prescriptionCalcul->getDistance($lng, $lat, $pharmacy);
                }
            }
        }


        return $this->render(self::ROLE . '/index.html.twig', [
            'prescription' => $prescription,
            'user' => $user,
            'pharmacies' => $pharmacies,
            'prices' => $prices,
            'distances' => $distances,
            'prefered' => $prefered,
            'zipcode' => $zipcode,
        ]);
    }

    /**
     * @Route("/prescription/{prescription}/sendto/{user}", name="_send_to_pharmacist")
     */
    public function sendToPharmacyst(Prescription $prescription, User $user, EntityManagerInterface $entityManager)
    {
        if (in_array("ROLE_PHARMACIST", $user->getRoles())) {
            $prescription->setPharmacist($user);
            $prescription->setBuy(new DateTime());
            $entityManager->flush();
        }

        return $this->redirectToRoute('patient_prescription_index');
    }
}
