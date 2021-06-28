<?php

namespace App\Controller;

use App\Entity\MessageSujet;
use App\Entity\Sujet;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\SujetFormType;
use App\Repository\SujetRepository;
use App\Form\SearchSubjectType;
use App\Form\MessageSujetType;
use App\Form\SearchSujetType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/sujet", name="sujet_")
 */
class SujetController extends AbstractController
{

    /**
     * Creat a new form in order to add a new subject
     * @IsGranted("ROLE_FREELANCE")
     * @Route("/new", name="new")
     */
    public function new(Request $request): Response
    {
        $sujet = new Sujet();
        $form = $this->createForm(SujetFormType::class, $sujet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sujet->setUser($this->getUser());
            $sujet->setDate(new \DateTime());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($sujet);
            $entityManager->flush();
            return $this->redirectToRoute('sujet_index');
        }

        return $this->render('sujet/new.html.twig', ["form" => $form->createView()]);
    }

    /**
     * Creat a new form in order to edit a subject
     * @Route("/{sujetId}/edit", name="edit")
     * @IsGranted("ROLE_FREELANCE")
     */
    public function edit(Request $request, Sujet $sujetId): Response
    {
        $form = $this->createForm(SujetFormType::class, $sujetId);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->redirectToRoute('sujet_index');
        }
        return $this->render('sujet/edit.html.twig', ["form" => $form->createView(), 'sujet' => $sujetId]);
    }


    /**
     * show all subjetc in the sujet section
     * @Route("/", name="index")
     * @IsGranted("ROLE_FREELANCE")
     */
    public function index(Request $request, SujetRepository $sujetRepository): Response
    {
        //on récupère tout ce qu'il y a dans la table Sujet
        $sujets = $this->getDoctrine()
            ->getRepository(Sujet::class)
            ->findAll();

        $form = $this->createForm(SearchSujetType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $search = $form->getData()['search'];
            $search = $search->getName();
            $sujets = $sujetRepository->findLikeName($search);
        } else {
            $sujets = $sujetRepository->findAll();
        }

        return $this->render('sujet/index.html.twig', [
            'sujets' => $sujets,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Show all comment of a subjet
     * @Route("/{id}", name="show")
     * @IsGranted("ROLE_FREELANCE")
     * @return Response A response instance
     */
    public function show(Sujet $sujet, Request $request): Response
    {
        $message = new MessageSujet();
        $form = $this->createForm(MessageSujetType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message->setUser($this->getUser());
            $message->setSujet($sujet);
            $message->setDate(new \DateTime());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($message);
            $entityManager->flush();
            return $this->redirectToRoute('sujet_show', ["id" => $sujet->getId()]);
        }

        return $this->render(
            'sujet/show.html.twig',
            [
                'sujet' => $sujet,
                "form" => $form->createView()
            ]
        );
    }

    /**
     * Delete a subject
     * @IsGranted("ROLE_FREELANCE")
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(Sujet $sujet): response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($sujet);
        $entityManager->flush();
        return $this->redirectToRoute('sujet_index');
    }
}
