<?php

namespace App\Controller;

use App\Entity\Prescription;
use App\Form\PrescriptionType;
use App\Repository\PrescriptionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/prescription")
 */
class PrescriptionController extends AbstractController
{
    /**
     * @Route("/", name="prescription_index", methods={"GET"})
     */
    public function index(PrescriptionRepository $prescriptionRepos): Response
    {
        return $this->render('prescription/index.html.twig', [
            'prescriptions' => $prescriptionRepos->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="prescription_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $prescription = new Prescription();
        $form = $this->createForm(PrescriptionType::class, $prescription);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($prescription);
            $entityManager->flush();

            return $this->redirectToRoute('prescription_index');
        }

        return $this->render('prescription/new.html.twig', [
            'prescription' => $prescription,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="prescription_show", methods={"GET"})
     */
    public function show(Prescription $prescription): Response
    {
        return $this->render('prescription/show.html.twig', [
            'prescription' => $prescription,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="prescription_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Prescription $prescription): Response
    {
        $form = $this->createForm(PrescriptionType::class, $prescription);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            /**
             * @var SubmitButton
             */
            $button = $form->get('addPrescriptionDrug');
            $route = $button->isClicked()
                ? 'prescription_drug_add'
                : 'prescription_edit';

            return $this->redirectToRoute($route, ['id' => $prescription->getId()]) ;
        }

        return $this->render('prescription/edit.html.twig', [
            'prescription' => $prescription,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="prescription_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Prescription $prescription): Response
    {
        if ($this->isCsrfTokenValid('delete'.$prescription->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($prescription);
            $entityManager->flush();
        }

        return $this->redirectToRoute('prescription_index');
    }
}
