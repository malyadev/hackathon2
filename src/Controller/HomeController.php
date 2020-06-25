<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HomeController
 *
 * @Route("/", name="home_")
 */
class HomeController extends AbstractController
{
    /**
     * @return Response
     *
     * @Route ("", name="index")
     */
    public function index() : Response
    {
        return $this->render('home/index.html.twig');
    }
}
