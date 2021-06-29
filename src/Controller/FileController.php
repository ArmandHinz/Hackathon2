<?php


namespace App\Controller;

use App\Entity\File;
use App\Entity\Projet;
use App\Form\UploadFileType;
use App\Services\Slugify;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class FileController
 * @package App\Controller
 * @Route("/file", name="file_")
 */
class FileController extends AbstractController
{

    /**
     * @param Request $request
     * @param Slugify $slugify
     * @param Projet $projet
     * @return Response
     * @Route("/", name="index")
     */
    public function index(Request $request, Slugify $slugify): Response
    {
        $files = $this->getDoctrine()->getRepository(File::class)->findAll();

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
                $file->setProject(null);
            }

            // ... persist the $file variable or any other work
            $manager = $this->getDoctrine()->getManager();

            $exists = $this->getDoctrine()->getRepository(File::class)->findOneBy(['name' => $safeFilename]);

            if(empty($exists)){
                $manager->persist($file);
                $manager->flush();
            }else {
                $this->addFlash('danger', 'file with this name already exists');
            }

            return $this->redirectToRoute('file_index');
        }

        return $this->render('file/index.html.twig', [
            'form' => $form->createView(), 'files' => $files,
        ]);
    }

    /**
     * @Route("/{id}/{projet}", name="delete", defaults={"projet": null})
     */
    public function delete(Request $request, File $file, int $projet): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($file);
        $entityManager->flush();

        if($projet !== null){
            return $this->redirectToRoute('projet_show', ['id' => $projet]);

        }
        return $this->redirectToRoute('file_index');
    }

}
