<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


    /**
     * @Route(name="home_")
     */
class HomeController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        if($this->getUser()){
        return $this->render('home/index.html.twig');
        } else {
            return $this->redirectToRoute('app_login');
        }
    }
}
