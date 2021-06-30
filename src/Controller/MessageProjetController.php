<?php

namespace App\Controller;

use App\Entity\Chanel;
use App\Entity\MessageChanel;
use App\Entity\MessageProjet;
use App\Entity\Projet;
use App\Form\MessageChanelType;
use App\Form\MessageProjetType;
use App\Repository\MessageChanelRepository;
use App\Repository\MessageProjetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessageProjetController extends AbstractController
{
    /**
     * @Route("project/message/{id}", name="message_projet")
     */
    public function index(Request $request, Projet $projet, MessageProjetRepository $messageProjetRepository): Response
    {
        $newMessage = new MessageProjet;
        $form = $this->createForm(MessageProjetType::class, $newMessage);
        $form->handleRequest($request);
        $IdProjet = $projet->getId();
        $messageProjet = $messageProjetRepository->findBy(['projet' => $IdProjet]);

        if ($form->isSubmitted() && $form->isValid()) {
            $newMessage->setProjet($projet);
            $newMessage->setUser($this->getUser());
            $newMessage->setDate(new \DateTime());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($newMessage);
            $entityManager->flush();

            return $this->redirectToRoute('message_projet', ['id' => $projet->getId()]);
        }
                
        return $this->render('message_projet/index.html.twig', [
            'projet' => $projet,
            'messages' => $messageProjet,
            'form' => $form->createView(),
        ]);
    }
}