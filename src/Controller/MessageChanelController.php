<?php

namespace App\Controller;

use App\Entity\Chanel;
use App\Entity\MessageChanel;
use App\Form\MessageChanelType;
use App\Repository\MessageChanelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessageChanelController extends AbstractController
{
    /**
     * @Route("/message/{id}", name="message_chanel")
     */
    public function index(Request $request, Chanel $chanel, MessageChanelRepository  $messageChanelRepository): Response
    {
        $newMessage = new messageChanel;
        $form = $this->createForm(messageChanelType::class, $newMessage);
        $form->handleRequest($request);
        $IdChanel = $chanel->getId();
        $messageChanel = $messageChanelRepository->findBy(['chanel' => $IdChanel]);

        if ($form->isSubmitted() && $form->isValid()) {
            $newMessage->setChanel($chanel);
            $newMessage->setUser($this->getUser());
            $newMessage->setDate(new \DateTime());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($newMessage);
            $entityManager->flush();

            return $this->redirectToRoute('message_chanel', ['id' => $chanel->getId()]);
        }
                
        return $this->render('message_chanel/index.html.twig', [
            'chanel' => $chanel,
            'messages' => $messageChanel,
            'form' => $form->createView(),
        ]);
    }
}
