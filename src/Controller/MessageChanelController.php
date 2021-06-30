<?php

namespace App\Controller;

use App\Entity\Chanel;
use App\Entity\File;
use App\Entity\MessageChanel;
use App\Form\MessageChanelType;
use App\Form\UploadFileType;
use App\Repository\MessageChanelRepository;
use App\Services\Slugify;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessageChanelController extends AbstractController
{
    /**
     * @Route("/message/{id}", name="message_chanel")
     */
    public function index(Request $request, Chanel $chanel, MessageChanelRepository  $messageChanelRepository, Slugify $slugify): Response
    {
        $files = $this->getDoctrine()->getRepository(File::class)->findBy(['project' => $chanel->getProjet()->getId()]);

        $file = new File();
        $formFile = $this->createForm(UploadFileType::class, $file);
        $formFile->handleRequest($request);

        if ($formFile->isSubmitted() && $formFile->isValid()) {

            $uploadedFile = $formFile->get('file')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($uploadedFile) {
                $extension = $uploadedFile->guessExtension();

                $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugify->generate($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$uploadedFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $uploadedFile->move(
                        $this->getParameter('file_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $file->setSrc($newFilename);
                $file->setName($safeFilename);
                $file->setType($extension);
                $file->setProject($chanel->getProjet());
            }

            // ... persist the $file variable or any other work
            $manager = $this->getDoctrine()->getManager();

            $exists = $this->getDoctrine()->getRepository(File::class)->findOneBy(['name' => $safeFilename, 'project' => $chanel->getProjet()->getId()]);

            if(empty($exists)){
                $manager->persist($file);
                $manager->flush();
            }else {
                $this->addFlash('danger', 'file with this name already exists');
            }
            return $this->redirectToRoute('message_chanel', ['id' => $chanel->getId()]);
        }

        $newMessage = new messageChanel;
        $form = $this->createForm(MessageChanelType::class, $newMessage);
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
            'files' => $files,
            'formFile' => $formFile->createView(),
        ]);
    }
}
