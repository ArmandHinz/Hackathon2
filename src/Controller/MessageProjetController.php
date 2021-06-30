<?php

namespace App\Controller;

use App\Entity\Chanel;
use App\Entity\File;
use App\Entity\MessageChanel;
use App\Entity\MessageProjet;
use App\Entity\Projet;
use App\Form\MessageChanelType;
use App\Form\MessageProjetType;
use App\Form\UploadFileType;
use App\Repository\MessageChanelRepository;
use App\Repository\MessageProjetRepository;
use App\Services\Slugify;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessageProjetController extends AbstractController
{
    /**
     * @Route("project/message/{id}", name="message_projet")
     */
    public function index(Request $request, Projet $projet, MessageProjetRepository $messageProjetRepository, Slugify $slugify): Response
    {
        $files = $this->getDoctrine()->getRepository(File::class)->findBy(['project' => $projet->getId()]);

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
                $file->setProject($projet);
            }

            // ... persist the $file variable or any other work
            $manager = $this->getDoctrine()->getManager();

            $exists = $this->getDoctrine()->getRepository(File::class)->findOneBy(['name' => $safeFilename, 'project' => $projet]);

            if(empty($exists)){
                $manager->persist($file);
                $manager->flush();
            }else {
                $this->addFlash('danger', 'file with this name already exists');
            }
            return $this->redirectToRoute('message_projet', ['id' => $projet->getId()]);
        }

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
            'files' => $files,
            'formFile' => $formFile->createView(),
        ]);
    }
}
