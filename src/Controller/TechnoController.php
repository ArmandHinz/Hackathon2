<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Techno;
use App\Form\TechnoType;

/**
 * @Route("/techno", name="techno_")
 */
class TechnoController extends AbstractController
{
    /**
     * Creat a new form in order to add a new subject
     * @Route("/new", name="new")
     */
    public function new(Request $request): Response
    {
        $techno = new Techno();
        $form = $this->createForm(TechnoType::class, $techno);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($techno);
            $entityManager->flush();
            return $this->redirectToRoute('user_show', ["id" => $this->getUser()->getId()]);
        }

        return $this->render('sujet/new.html.twig', ["form" => $form->createView()]);
    }
}
