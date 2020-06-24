<?php

namespace App\Controller;

use App\Repository\PharmacyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/patient", name="patient")
 */
class PatientController extends AbstractController
{
    const ROLE = 'patient';
    /**
     * @Route("/prescription/index", name="_prescription_index")
     */
    public function index(PharmacyRepository $pharmacyRepository)
    {
        $pharmacies=$pharmacyRepository->findBy(
            [],
            [],
            3,
            0
        );

        return $this->render(self::ROLE.'/index.html.twig', ['pharmacies'=>$pharmacies]);
    }
}
