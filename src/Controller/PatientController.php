<?php

namespace App\Controller;

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
    public function index()
    {
        $pharmacies=[1,2,3];
        return $this->render(self::ROLE.'/index.html.twig', ['pharmacies'=>$pharmacies]);
    }
}
