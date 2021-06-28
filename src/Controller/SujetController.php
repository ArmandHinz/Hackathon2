<?php

namespace App\Controller;

use App\Entity\MessageSujet;
use App\Entity\Sujet;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\SujetFormType;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use App\Repository\SujetRepository;
use App\Form\SearchSubjectType;
use App\Form\MessageSujetType;
use App\Form\SearchSujetType;


/**
 * @Route("/sujet", name="sujet_")
 */
class SujetController extends AbstractController
{

    /**
     * Creat a new form in order to add a new subject
     *
     * @Route("/new", name="new")
     */
    public function new(Request $request, MailerInterface $mailer): Response
    {

        $sujet = new Sujet();
        $form = $this->createForm(SujetFormType::class, $sujet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //$sujet->setAuthor($this->getUser()); à mettre quand on aura les sessions
            $sujet->setDate(new \DateTime());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($sujet);
            $entityManager->flush();
            /**$email = (new Email())
                ->from('11ff993407-658458@inbox.mailtrap.io')
                ->to('11ff993407-658458@inbox.mailtrap.io')
                ->subject('Une nouvelle série vient d\'être publiée !')
                ->html($this->renderView('sujet/newsujetEmail.html.twig', ['sujet' => $sujet]));

            $mailer->send($email);*/
            return $this->redirectToRoute('sujet_index');
        }

        return $this->render('sujet/new.html.twig', ["form" => $form->createView()]);
    }

    /**
     * Creat a new form in order to edit a subject
     * @Route("/{sujetId}/edit", name="edit")
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
     * @return Response A response instance
     */
    public function show(Sujet $sujet, Request $request): Response
    {
        $message = new MessageSujet();
        $form = $this->createForm(MessageSujetType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //$message->setUser($this->getUser()); à mettre quand on aura les sessions
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
     *
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
