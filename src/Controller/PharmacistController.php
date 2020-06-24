<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * @Route("/pharmacist", name="pharmacist")
 */
class PharmacistController extends AbstractController
{
    const ROLE = 'pharmacist';
    /**
     * @Route("/prescription/index", name="_prescription_index")
     */
    public function index()
    {
        return $this->render(self::ROLE.'/index.html.twig');
    }
}
