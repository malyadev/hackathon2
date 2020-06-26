<?php

namespace App\Controller;

use App\Entity\Prescription;
use App\Form\PhPrescriptionType;
use App\Form\PrescriptionType;
use App\Repository\UserRepository;
use DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/prescription")
 */
class PrescriptionController extends AbstractController
{
    /**
     * @Route("/", name="prescription_index", methods={"GET"})
     * @IsGranted({"ROLE_PRACTITIONER", "ROLE_PHARMACIST"})
     */
    public function index(?UserInterface $user): Response
    {
        $route="";
        if ($user!=null) {
            if (in_array("ROLE_PRACTITIONER", $user->getRoles())) {
                $route='practitioner';
            }

            if (in_array("ROLE_PHARMACIST", $user->getRoles())) {
                $route='pharmacist';
            }

            if (in_array("ROLE_PATIENT", $user->getRoles())) {
                $route='patient';
            }
        }


        return $this->redirectToRoute($route.'_prescription_index');
    }

    /**
     * @Route("/new", name="prescription_new", methods={"GET","POST"})
     * @IsGranted("ROLE_PRACTITIONER")
     */
    public function new(Request $request, UserRepository $userRepository, ?UserInterface $user): Response
    {
        $prescription = new Prescription();
        $form = $this->createForm(PrescriptionType::class, $prescription);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $socialNumber=$form['user']->getData();
            $patient=$userRepository->findOneBy(['socialNumber'=>$socialNumber]);

            $prescription->setUser($patient);
            $prescription->setPractitioner($user);

            $prescription->setCreation(new DateTime());
            $entityManager->persist($prescription);
            $entityManager->flush();

            return $this->redirectToRoute('prescription_edit', ['id' => $prescription->getId()]);
        }

        return $this->render('prescription/new.html.twig', [
            'prescription' => $prescription,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="prescription_show", methods={"GET"})
     * @IsGranted({"ROLE_PRACTITIONER", "ROLE_PHARMACIST"})
     */
    public function show(Prescription $prescription): Response
    {
        return $this->render('prescription/show.html.twig', [
            'prescription' => $prescription,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="prescription_edit", methods={"GET","POST"})
     * @IsGranted({"ROLE_PRACTITIONER", "ROLE_PHARMACIST"})
     */
    public function edit(Request $request, Prescription $prescription, ?UserInterface $user): Response
    {
        $this->denyAccessUnlessGranted('edit', $prescription);

        $form = $this->createForm(PrescriptionType::class, $prescription);
        $view = 'prescription/edit.html.twig';

        if (!is_null($user) && in_array("ROLE_PHARMACIST", $user->getRoles())) {
            $form = $this->createForm(PhPrescriptionType::class, $prescription);
            //$view = 'prescription/edit.html.twig';
        }

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

        return $this->render($view, [
            'prescription' => $prescription,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="prescription_delete", methods={"DELETE"})
     * @IsGranted("ROLE_PRACTITIONER")
     */
    public function delete(Request $request, Prescription $prescription): Response
    {
        $this->denyAccessUnlessGranted('delete', $prescription);

        if ($this->isCsrfTokenValid('delete'.$prescription->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($prescription);
            $entityManager->flush();
        }

        return $this->redirectToRoute('prescription_index');
    }
}
