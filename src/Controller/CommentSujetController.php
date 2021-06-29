<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\MessageSujet;
use App\Form\MessageSujetType;


/**
 * @Route("/sujet_comment", name="comment_sujet_")
 */
class CommentSujetController extends AbstractController
{
    /**
     * Creat a new form in order to add a new subject
     *
     * @Route("/{id}/edit", name="edit")
     */
    public function new(Request $request, MessageSujet $message): Response
    {
        $form = $this->createForm(MessageSujetType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->redirectToRoute('sujet_show', ["id" => $message->getSujet()->getId()]);
        }

        return $this->render('comment_sujet/new.html.twig', ["form" => $form->createView()]);
    }

    /**
     * Delete a subject
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(MessageSujet $messageSujet): response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($messageSujet);
        $entityManager->flush();
        return $this->redirectToRoute('sujet_index');
    }
}
