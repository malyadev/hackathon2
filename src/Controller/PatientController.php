<?php

namespace App\Controller;

use App\Entity\Prescription;
use App\Entity\PrescriptionDrug;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
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
    public function index(? UserInterface $user)
    {
        $prescription = $this->getDoctrine()
            ->getRepository(Prescription::class)
            ->findOneBy(
                ['user' => $user]
            );

        if (!is_null($prescription)) {
            $prescriptionDrugs = $this->getDoctrine()
                ->getRepository(PrescriptionDrug::class)
                ->findBy(
                    ['prescription' => $prescription]
                );

            if ($prescriptionDrugs) {
                return $this->render(self::ROLE . '/index.html.twig', [
                    'prescriptionDrugs' => $prescriptionDrugs,
                    'user' => $user
                ]);
            }
        }
    }
}
