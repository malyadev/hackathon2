<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

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
        return $this->render(self::ROLE.'/index.html.twig');
    }
}
