<?php

namespace App\Controller;

use App\Repository\PrescriptionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/pharmacist", name="pharmacist")
 */
class PharmacistController extends AbstractController
{
    const ROLE = 'pharmacist';
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
