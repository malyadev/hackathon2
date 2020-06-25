<?php

namespace App\Controller;

use App\Entity\PrescriptionDrug;
use App\Form\PrescriptionDrugType;
use App\Repository\PrescriptionDrugRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/prescription/drug")
 */
class PrescriptionDrugController extends AbstractController
{
    /**
     * @Route("/", name="prescription_drug_index", methods={"GET"})
     */
    public function index(PrescriptionDrugRepository $prescDrugRepos): Response
    {
        return $this->render('prescription_drug/index.html.twig', [
            'prescription_drugs' => $prescDrugRepos->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="prescription_drug_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $prescriptionDrug = new PrescriptionDrug();
        $form = $this->createForm(PrescriptionDrugType::class, $prescriptionDrug);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($prescriptionDrug);
            $entityManager->flush();

            return $this->redirectToRoute('prescription_drug_index');
        }

        return $this->render('prescription_drug/new.html.twig', [
            'prescription_drug' => $prescriptionDrug,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="prescription_drug_show", methods={"GET"})
     */
    public function show(PrescriptionDrug $prescriptionDrug): Response
    {
        return $this->render('prescription_drug/show.html.twig', [
            'prescription_drug' => $prescriptionDrug,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="prescription_drug_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, PrescriptionDrug $prescriptionDrug): Response
    {
        $form = $this->createForm(PrescriptionDrugType::class, $prescriptionDrug);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('prescription_drug_index');
        }

        return $this->render('prescription_drug/edit.html.twig', [
            'prescription_drug' => $prescriptionDrug,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="prescription_drug_delete", methods={"DELETE"})
     */
    public function delete(Request $request, PrescriptionDrug $prescriptionDrug): Response
    {
        if ($this->isCsrfTokenValid('delete'.$prescriptionDrug->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($prescriptionDrug);
            $entityManager->flush();
        }

        return $this->redirectToRoute('prescription_drug_index');
    }
}
