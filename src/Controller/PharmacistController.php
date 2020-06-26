<?php

namespace App\Controller;

use App\Entity\Prescription;
use App\Repository\PrescriptionRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
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

    /**
     * @Route("/prescription/{id}/ready", name="_prescription_ready")
     */
    public function setPrescriptionReady(Prescription $prescription, EntityManagerInterface $entityManager)
    {
        $prescription->setPreparation(new DateTime());
        $entityManager->flush();

        return $this->redirectToRoute('pharmacist_prescription_index');
    }

    /**
     * @Route("/prescription/{id}/deliver", name="_prescription_deliver")
     */
    public function setPrescriptionDelivered(Prescription $prescription, EntityManagerInterface $entityManager)
    {
        $prescription->setDelivery(new DateTime());
        $entityManager->flush();

        return $this->redirectToRoute('pharmacist_prescription_index');
    }
}
