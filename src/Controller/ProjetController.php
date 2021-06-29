<?php

namespace App\Controller;

use App\Entity\File;
use App\Entity\Projet;
use App\Form\ProjetType;
use App\Form\UploadFileType;
use App\Repository\ProjetRepository;
use App\Services\Slugify;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


/**
 * @Route("/projet", name="projet_")
 */
class ProjetController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(ProjetRepository $projetRepository): Response
    {

        return $this->render('projet/index.html.twig', [
            'projets' => $projetRepository->findAll()
        ]);
    }

    /**
     * @Route("/new", name="new")
     * @IsGranted("ROLE_BUSINESS")
     */
    public function new(Request $request): Response
    {
        $projet = new Projet;
        $form = $this->createForm(ProjetType::class, $projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $projet->setUser($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($projet);
            $entityManager->flush();

            return $this->redirectToRoute('projet_index');
        }

        return $this->render('projet/new.html.twig', [
            'projet' => $projet,
            'form' => $form->createView(),
        ]);

    }

    /**
     * @Route("/{id}/edit", name="edit")
     * @IsGranted("ROLE_BUSINESS")
     */
    public function edit(Request $request, Projet $projet): Response
    {
        if (!($this->getUser() == $projet->getUser())) {
            // If not the owner, throws a 403 Access Denied exception
            throw new AccessDeniedException('Only the owner can edit the program!');
        }
        $form = $this->createForm(ProjetType::class, $projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('projet_index');
        }

        return $this->render('projet/edit.html.twig', [
            'projet' => $projet,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="show")
     */
    public function show(Projet $projet, Request $request, Slugify $slugify): Response
    {
        $files = $this->getDoctrine()->getRepository(File::class)->findBy(['project' => $projet->getId()]);

        $file = new File();
        $form = $this->createForm(UploadFileType::class, $file);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $uploadedFile = $form->get('file')->getData();

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

            $exists = $this->getDoctrine()->getRepository(File::class)->findOneBy(['name' => $safeFilename, 'project' => $projet->getId()]);

            if(empty($exists)){
                $manager->persist($file);
                $manager->flush();
            }else {
                $this->addFlash('danger', 'file with this name already exists');
            }

            return $this->redirectToRoute('projet_show', ['id' => $projet->getId()]);
        }
        return $this->render('projet/show.html.twig', ['projet' => $projet, 'files' => $files, 'form' => $form->createView()]);
    }

    /**
     * @Route("/{id}/delete", name="delete")
     * @IsGranted("ROLE_BUSINESS")
     */
    public function delete(Request $request, Projet $projet): Response
    {
        if (!($this->getUser() == $projet->getUser())) {
            // If not the owner, throws a 403 Access Denied exception
            throw new AccessDeniedException('Only the owner can edit the program!');
        }
        if ($this->isCsrfTokenValid('delete'.$projet->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($projet);
            $entityManager->flush();
        }
        return $this->redirectToRoute('projet_index');
    }
}
