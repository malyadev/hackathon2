<?php

namespace App\Controller;

use App\Repository\PrescriptionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/practitioner", name="practitioner")
 */
class PractitionerController extends AbstractController
{
    const ROLE = 'practitioner';
    /**
     * @Route("/prescription/index", name="_prescription_index")
     */
    public function index(PrescriptionRepository $prescription)
    {
        return $this->render('prescription/index.html.twig', [
            'prescriptions' => $prescription->findAll(),
        ]);
    }
}
