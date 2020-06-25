<?php

namespace App\Controller;

use App\Entity\Prescription;
use App\Form\PrescriptionType;
use App\Repository\PrescriptionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/practitioner", name="practitioner")
 */
class PractitionerController extends AbstractController
{
    const ROLE = 'practitioner';
    /**
     * @Route("/prescription/index", name="_prescription_index")
     */
    public function index(PrescriptionRepository $prescriptionRepos, ?UserInterface $user)
    {
        $prescriptions= $prescriptionRepos->findBy([
            self::ROLE => $user
        ]);

        return $this->render('prescription/index.html.twig', [
            'prescriptions' => $prescriptions,
        ]);
    }
}
